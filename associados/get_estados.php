<?php
// Inclui o arquivo de conexão
include_once "conexao.php";

// Define o cabeçalho para JSON
header('Content-Type: application/json');

// Verifica se a conexão foi bem-sucedida
if (!$conn) {
    // Se a conexão falhar, retorna um erro em formato JSON
    echo json_encode(['error' => 'Erro ao conectar ao banco de dados']);
    exit;
}

try {
    // Consulta para obter os estados
    $query_estados = "SELECT id, nome, uf FROM estados";
    $result_estados = $conn->query($query_estados);

    // Verifica se a consulta foi bem-sucedida
    if ($result_estados) {
        // Se a consulta for bem-sucedida, transforma os resultados em um array associativo
        $estados = $result_estados->fetchAll(PDO::FETCH_ASSOC);
        // Retorna os dados em formato JSON
        echo json_encode(['estados' => $estados]);
    } else {
        // Se a consulta falhar, retorna um erro em formato JSON
        echo json_encode(['error' => 'Erro ao consultar os estados']);
    }
} catch (Exception $e) {
    // Em caso de erro, retorna a mensagem de erro
    echo json_encode(['error' => 'Erro ao consultar estados: ' . $e->getMessage()]);
}
?>
