<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber os dados enviados pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

try {
    // Iniciar a transação
    $conn->beginTransaction();
    
    // Atualizar o evento
    $query_edit_event = "UPDATE jogos SET title=:title, color=:color, start=:start, end=:end, adversario=:adversario, genero=:genero WHERE id=:id";
    $edit_event = $conn->prepare($query_edit_event);
    $edit_event->bindParam(':title', $dados['edit_title']);
    $edit_event->bindParam(':color', $dados['edit_color']);
    $edit_event->bindParam(':start', $dados['edit_start']);
    $edit_event->bindParam(':end', $dados['edit_end']);
    $edit_event->bindParam(':genero', $dados['edit_genero']);
    $edit_event->bindParam(':adversario', $dados['edit_adversario']);
    $edit_event->bindParam(':id', $dados['edit_id']);
    $edit_event->execute();

    // Atualizar a modalidade associada ao evento
    $query_delete_modalidade_jogo = "DELETE FROM modalidade_jogo WHERE jogo_id=:jogo_id";
    $delete_modalidade_jogo = $conn->prepare($query_delete_modalidade_jogo);
    $delete_modalidade_jogo->bindParam(':jogo_id', $dados['edit_id']);
    $delete_modalidade_jogo->execute();

    $query_insert_modalidade_jogo = "INSERT INTO modalidade_jogo (modalidade_id, jogo_id) VALUES (:modalidade_id, :jogo_id)";
    $insert_modalidade_jogo = $conn->prepare($query_insert_modalidade_jogo);
    $insert_modalidade_jogo->bindParam(':modalidade_id', $dados['edit_modalidade']);
    $insert_modalidade_jogo->bindParam(':jogo_id', $dados['edit_id']);
    $insert_modalidade_jogo->execute();

    // Atualizar os associados relacionados ao evento
    $query_delete_associado_jogo = "DELETE FROM associado_jogo WHERE jogo_id=:jogo_id";
    $delete_associado_jogo = $conn->prepare($query_delete_associado_jogo);
    $delete_associado_jogo->bindParam(':jogo_id', $dados['edit_id']);
    $delete_associado_jogo->execute();

    if (!empty($dados['associados'])) {
        foreach ($dados['associados'] as $associado_id) {
            $query_insert_associado_jogo = "INSERT INTO associado_jogo (associado_id, jogo_id) VALUES (:associado_id, :jogo_id)";
            $insert_associado_jogo = $conn->prepare($query_insert_associado_jogo);
            $insert_associado_jogo->bindParam(':associado_id', $associado_id);
            $insert_associado_jogo->bindParam(':jogo_id', $dados['edit_id']);
            $insert_associado_jogo->execute();
        }
    }

    // Recuperar os dados atualizados para retorno
    $query_event_details = "SELECT title, color, start, end, adversario, genero FROM jogos WHERE id=:id";
    $get_event_details = $conn->prepare($query_event_details);
    $get_event_details->bindParam(':id', $dados['edit_id']);
    $get_event_details->execute();
    $event_details = $get_event_details->fetch(PDO::FETCH_ASSOC);

    // Recuperar modalidade associada
    $query_modalidade = "SELECT modalidade_id FROM modalidade_jogo WHERE jogo_id=:jogo_id";
    $get_modalidade = $conn->prepare($query_modalidade);
    $get_modalidade->bindParam(':jogo_id', $dados['edit_id']);
    $get_modalidade->execute();
    $modalidade = $get_modalidade->fetchColumn();

    // Recuperar associados
    $query_associados = "SELECT associado_id FROM associado_jogo WHERE jogo_id=:jogo_id";
    $get_associados = $conn->prepare($query_associados);
    $get_associados->bindParam(':jogo_id', $dados['edit_id']);
    $get_associados->execute();
    $associados = $get_associados->fetchAll(PDO::FETCH_COLUMN);

    // Confirmar a transação
    $conn->commit();

    $retorna = [
        'status' => true, 
        'msg' => 'Evento editado com sucesso!', 
        'id' => $dados['edit_id'], 
        'title' => $event_details['title'], 
        'adversario' => $event_details['adversario'], 
        'color' => $event_details['color'], 
        'start' => $event_details['start'], 
        'end' => $event_details['end'],
        'genero' => $event_details['genero'],
        'modalidade' => $modalidade,
        'associados' => $associados
    ];

} catch (PDOException $e) {
    // Reverter a transação em caso de erro
    $conn->rollBack();
    $retorna = ['status' => false, 'msg' => 'Erro: ' . $e->getMessage()];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);
?>
