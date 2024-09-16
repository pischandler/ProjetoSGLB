<?php

// Exibir erros no PHP para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir o cabeçalho para retorno JSON
header('Content-Type: application/json');

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber os dados enviados pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Criar a QUERY para editar o evento no banco de dados
$query_edit_event = "UPDATE events SET title=:title, color=:color, start=:start, end=:end, cidade_id=:cidade_id WHERE id=:id";

// Prepara a QUERY
$edit_event = $conn->prepare($query_edit_event);

// Substituir o link pelo valor
$edit_event->bindParam(':title', $dados['edit_title']);
$edit_event->bindParam(':color', $dados['edit_color']);
$edit_event->bindParam(':start', $dados['edit_start']);
$edit_event->bindParam(':end', $dados['edit_end']);
$edit_event->bindParam(':cidade_id', $dados['edit_cidade']);  // Adicionar cidade_id
$edit_event->bindParam(':id', $dados['edit_id']);

// Verificar se conseguiu editar corretamente
if ($edit_event->execute()) {
    // Consulta para obter os nomes da cidade e do estado após a atualização
    $query_info_local = "SELECT c.nome AS cidade_nome, es.nome AS estado_nome, es.uf AS estado_uf
                         FROM cidades c
                         LEFT JOIN estados es ON c.id_estado = es.id
                         WHERE c.id = :cidade_id";
    
    $info_local = $conn->prepare($query_info_local);
    $info_local->bindParam(':cidade_id', $dados['edit_cidade']); // Bind cidade_id
    $info_local->execute();
    $local = $info_local->fetch(PDO::FETCH_ASSOC);

    // Retornar os dados atualizados
    $retorna = [
        'status' => true, 
        'msg' => 'Evento editado com sucesso!', 
        'id' => $dados['edit_id'], 
        'title' => $dados['edit_title'], 
        'color' => $dados['edit_color'], 
        'start' => $dados['edit_start'], 
        'end' => $dados['edit_end'],
        'cidade_id' => $dados['edit_cidade'],
        'cidade_nome' => $local['cidade_nome'] ?? '',  // Garantir que não retorna null
        'estado_nome' => $local['estado_nome'] ?? '',   // Garantir que não retorna null
        'estado_uf' => $local['estado_uf'] ?? ''        // Garantir que não retorna null
    ];
} else {
    $retorna = ['status' => false, 'msg' => 'Erro: Evento não editado!'];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);
