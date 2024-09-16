<?php
// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber os dados enviados pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Verificar se os campos obrigatórios estão presentes e válidos
if (empty($dados['edit_title'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Categoria.']);
    exit;
}

if (empty($dados['edit_adversario'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Adversário']);
    exit;
}
if (empty($dados['edit_genero'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Gênero']);
    exit;
}
if (empty($dados['edit_modalidade'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário selecionar a Modalidade']);
    exit;
}
if (empty($dados['edit_cidade'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário selecionar a Cidade']);
    exit;
}

// Verificação adicional para os novos campos
if (empty($dados['edit_local'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Local']);
    exit;
}

if (empty($dados['edit_rua'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Rua']);
    exit;
}

if (empty($dados['edit_bairro'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Bairro']);
    exit;
}

if (empty($dados['edit_numero'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Número']);
    exit;
}

if (empty($dados['edit_cep'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo CEP']);
    exit;
}
if (!preg_match('/^\d{5}-?\d{3}$/', $dados['edit_cep'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: O CEP deve conter 8 dígitos numéricos, com ou sem hífen.']);
    exit;
}

try {
    // Iniciar a transação
    $conn->beginTransaction();
    
    // Atualizar o evento
    $query_edit_event = "UPDATE jogos SET title=:title, color=:color, start=:start, end=:end, adversario=:adversario, genero=:genero, cidade_id=:cidade_id, rua=:rua, local=:local, cep=:cep, bairro=:bairro, numero=:numero, complemento=:complemento WHERE id=:id";
    $edit_event = $conn->prepare($query_edit_event);
    $edit_event->bindParam(':title', $dados['edit_title']);
    $edit_event->bindParam(':color', $dados['edit_color']);
    $edit_event->bindParam(':start', $dados['edit_start']);
    $edit_event->bindParam(':end', $dados['edit_end']);
    $edit_event->bindParam(':genero', $dados['edit_genero']);
    $edit_event->bindParam(':adversario', $dados['edit_adversario']);
    $edit_event->bindParam(':cidade_id', $dados['edit_cidade']);
    $edit_event->bindParam(':rua', $dados['edit_rua']);
    $edit_event->bindParam(':local', $dados['edit_local']);
    $edit_event->bindParam(':cep', $dados['edit_cep']);
    $edit_event->bindParam(':bairro', $dados['edit_bairro']);
    $edit_event->bindParam(':numero', $dados['edit_numero']);
    $edit_event->bindParam(':complemento', $dados['edit_complemento']);
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
        $query_insert_associado_jogo = "INSERT INTO associado_jogo (associado_id, jogo_id) VALUES (:associado_id, :jogo_id)";
        $insert_associado_jogo = $conn->prepare($query_insert_associado_jogo);
        foreach ($dados['associados'] as $associado_id) {
            $insert_associado_jogo->bindParam(':associado_id', $associado_id);
            $insert_associado_jogo->bindParam(':jogo_id', $dados['edit_id']);
            $insert_associado_jogo->execute();
        }
    }

    // Confirmar a transação
    $conn->commit();

    // Recuperar os dados atualizados do jogo
    $query_event_details = "SELECT title, color, start, end, adversario, genero, cidade_id, rua, local, cep, bairro, numero, complemento FROM jogos WHERE id=:id";
    $get_event_details = $conn->prepare($query_event_details);
    $get_event_details->bindParam(':id', $dados['edit_id']);
    $get_event_details->execute();
    $event_details = $get_event_details->fetch(PDO::FETCH_ASSOC);

    // Consultar os dados da cidade e estado associados
    $query_info_local = "SELECT c.nome AS cidade_nome, es.nome AS estado_nome, es.uf AS estado_uf
                         FROM cidades c
                         LEFT JOIN estados es ON c.id_estado = es.id
                         WHERE c.id = :cidade_id";
    $info_local = $conn->prepare($query_info_local);
    $info_local->bindParam(':cidade_id', $event_details['cidade_id']);
    $info_local->execute();
    $local = $info_local->fetch(PDO::FETCH_ASSOC);

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
        'associados' => $associados,
        'cidade_nome' => $local['cidade_nome'] ?? '',  // Garantir que não retorna null
        'estado_nome' => $local['estado_nome'] ?? '',   // Garantir que não retorna null
        'estado_uf' => $local['estado_uf'] ?? '',        // Garantir que não retorna null
        // Incluindo os novos campos na resposta
        'rua' => $event_details['rua'],
        'local' => $event_details['local'],
        'bairro' => $event_details['bairro'],
        'numero' => $event_details['numero'],
        'complemento' => $event_details['complemento'],
        'cep' => $event_details['cep']
    ];

} catch (PDOException $e) {
    // Reverter a transação em caso de erro
    $conn->rollBack();
    $retorna = ['status' => false, 'msg' => 'Erro: ' . $e->getMessage()];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);
?>
