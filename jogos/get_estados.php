<?php
include_once "conexao.php";

header('Content-Type: application/json');

$query_estados = "SELECT id, nome, uf FROM estados";
$result_estados = $conn->query($query_estados);

if ($result_estados) {
    $estados = $result_estados->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['estados' => $estados]);
} else {
    echo json_encode(['estados' => []]);
}
?>
