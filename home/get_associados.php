<?php
include_once './conexao.php';

try {
    $query = "SELECT id, nome FROM associados";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $associados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($associados);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
}
?>
