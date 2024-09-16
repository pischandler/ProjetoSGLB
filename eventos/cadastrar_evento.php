<?php

// Exibir erros no PHP para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir o cabeçalho para retorno JSON
header('Content-Type: application/json');

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber os dados enviado pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Criar a QUERY cadastrar evento no banco de dados
$query_cad_event = "INSERT INTO events (title, color, start, end, cidade_id) VALUES (:title, :color, :start, :end, :cidade_id)";

// Prepara a QUERY
$cad_event = $conn->prepare($query_cad_event);

// Substituir o link pelo valor
$cad_event->bindParam(':title', $dados['cad_title']);
$cad_event->bindParam(':color', $dados['cad_color']);
$cad_event->bindParam(':start', $dados['cad_start']);
$cad_event->bindParam(':end', $dados['cad_end']);
$cad_event->bindParam(':cidade_id', $dados['cad_cidade']);

// Verificar se conseguiu cadastrar corretamente
if ($cad_event->execute()) {
    // Obter o ID do último evento cadastrado
    $evento_id = $conn->lastInsertId();

    // Consulta para obter os nomes da cidade e do estado
    $query_info_local = "SELECT c.nome AS cidade_nome, es.nome AS estado_nome, es.uf AS estado_uf
                         FROM cidades c
                         LEFT JOIN estados es ON c.id_estado = es.id
                         WHERE c.id = :cidade_id";
    
    $info_local = $conn->prepare($query_info_local);
    $info_local->bindParam(':cidade_id', $dados['cad_cidade']);
    $info_local->execute();
    $local = $info_local->fetch(PDO::FETCH_ASSOC);

    // Retornar os dados
    $retorna = [
        'status' => true, 
        'msg' => 'Evento cadastrado com sucesso!', 
        'id' => $evento_id, 
        'title' => $dados['cad_title'], 
        'color' => $dados['cad_color'], 
        'start' => $dados['cad_start'], 
        'end' => $dados['cad_end'],
        'cidade_id' => $dados['cad_cidade'],
        'cidade_nome' => $local['cidade_nome'] ?? '',  // Garante que não vai retornar null
        'estado_nome' => $local['estado_nome'] ?? '',   // Garante que não vai retornar null
        'estado_uf' => $local['estado_uf'] ?? ''        // Garante que não vai retornar null
    ];
} else {
    $retorna = ['status' => false, 'msg' => 'Erro: Evento não cadastrado!'];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);
