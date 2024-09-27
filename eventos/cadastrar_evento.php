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

if (empty($dados['cad_title'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Título.']);
    exit;
}

if (empty($dados['cad_cidade'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário selecionar a Cidade']);
    exit;
}

// Validação dos novos campos

// Verificar se o campo Local está preenchido
if (empty($dados['cad_local'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Local']);
    exit;
}

// Verificar se o campo Rua está preenchido
if (empty($dados['cad_rua'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Rua']);
    exit;
}

// Verificar se o campo Bairro está preenchido
if (empty($dados['cad_bairro'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Bairro']);
    exit;
}

// Verificar se o campo Número está preenchido
if (empty($dados['cad_numero'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Número']);
    exit;
}

// Verificar se o CEP está preenchido e se tem o formato correto
if (empty($dados['cad_cep'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo CEP']);
    exit;
}if (!preg_match('/^\d{5}-?\d{3}$/', $dados['cad_cep'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: O CEP deve conter 8 dígitos numéricos, com ou sem hífen.']);
    exit;
}
// Criar a QUERY para cadastrar evento no banco de dados com os novos campos
$query_cad_event = "INSERT INTO events (title, color, start, end, cidade_id, cep, rua, local, bairro, numero, complemento) 
                    VALUES (:title, :color, :start, :end, :cidade_id, :cep, :rua, :local, :bairro, :numero, :complemento)";

// Prepara a QUERY
$cad_event = $conn->prepare($query_cad_event);

// Substituir os valores dos parâmetros
$cad_event->bindParam(':title', $dados['cad_title']);
$cad_event->bindParam(':color', $dados['cad_color']);
$cad_event->bindParam(':start', $dados['cad_start']);
$cad_event->bindParam(':end', $dados['cad_end']);
$cad_event->bindParam(':cidade_id', $dados['cad_cidade']);
$cad_event->bindParam(':cep', $dados['cad_cep']);
$cad_event->bindParam(':rua', $dados['cad_rua']);
$cad_event->bindParam(':local', $dados['cad_local']);
$cad_event->bindParam(':bairro', $dados['cad_bairro']);
$cad_event->bindParam(':numero', $dados['cad_numero']);
$cad_event->bindParam(':complemento', $dados['cad_complemento']);

// Verificar se o cadastro foi realizado corretamente
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
        'cep' => $dados['cad_cep'],
        'rua' => $dados['cad_rua'],
        'local' => $dados['cad_local'],
        'bairro' => $dados['cad_bairro'],
        'numero' => $dados['cad_numero'],
        'complemento' => $dados['cad_complemento'],
        'cidade_nome' => $local['cidade_nome'] ?? '',  // Garante que não vai retornar null
        'estado_nome' => $local['estado_nome'] ?? '',   // Garante que não vai retornar null
        'estado_uf' => $local['estado_uf'] ?? ''        // Garante que não vai retornar null
    ];
} else {
    $retorna = ['status' => false, 'msg' => 'Erro: Evento não cadastrado!'];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);

