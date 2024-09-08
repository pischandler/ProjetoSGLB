<?php
// Conectar ao banco de dados
include 'conexao.php';

// Receber os dados enviados pelo JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['placar_casa'], $data['placar_adversario'])) {
    $id = $data['id'];
    $placarCasa = $data['placar_casa'];
    $placarAdversario = $data['placar_adversario'];

    // Usando PDO para preparar e executar a consulta
    $sql = "UPDATE jogos SET placar_casa = ?, placar_adversario = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Verificar se a preparação foi bem-sucedida
    if ($stmt) {
        $stmt->execute([$placarCasa, $placarAdversario, $id]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
}
?>
