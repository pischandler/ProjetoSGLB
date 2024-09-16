<?php
include_once "conexao.php";

header('Content-Type: application/json');

$estado_id = filter_input(INPUT_GET, 'estado_id', FILTER_SANITIZE_NUMBER_INT);

if ($estado_id) {
    $query_cidades = "SELECT id, nome FROM cidades WHERE id_estado = :estado_id";
    $stmt_cidades = $conn->prepare($query_cidades);
    $stmt_cidades->bindParam(':estado_id', $estado_id);
    $stmt_cidades->execute();
    
    $cidades = $stmt_cidades->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['cidades' => $cidades]);
} else {
    echo json_encode(['cidades' => []]);
}
?>
