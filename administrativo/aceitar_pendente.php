<?php
// Incluir a conexão com o banco de dados
include_once "conexao.php";

// Receber o ID do usuário a ser aceito via POST
$id_usuario = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

// Verificar se o ID foi passado e se é válido
if (!empty($id_usuario)) {
    // Consulta para atualizar o status do usuário para "ativo"
    $query_aceitar_usuario = "UPDATE usuarios SET status = 'ativo' WHERE id = :id AND status = 'pendente'";
    $stmt = $conn->prepare($query_aceitar_usuario);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    // Tentar executar a consulta e verificar se houve sucesso
    if ($stmt->execute()) {
        // Retornar uma resposta de sucesso
        $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Solicitação aceita!</div>"];
    } else {
        // Retornar uma resposta de erro
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: falha ao aceitar solicitação!</div>"];
    }
} else {
    // Se o ID não for válido, retornar uma resposta de erro
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
}

// Retornar a resposta em formato JSON
echo json_encode($retorna); 
?>
