<?php

include_once "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    try {
        // Inicia uma transação
        $conn->beginTransaction();

        // Apaga as modalidades associadas ao associado
        $query_modalidades = "DELETE FROM associado_modalidade WHERE associado_id=:id";
        $result_modalidades = $conn->prepare($query_modalidades);
        $result_modalidades->bindParam(':id', $id);
        $result_modalidades->execute();

        // Apaga os jogos associados ao associado
        $query_jogos = "DELETE FROM associado_jogo WHERE associado_id=:id";
        $result_jogos = $conn->prepare($query_jogos);
        $result_jogos->bindParam(':id', $id);
        $result_jogos->execute();

        // Apaga o associado
        $query_associado = "DELETE FROM associados WHERE id=:id";
        $result_associado = $conn->prepare($query_associado);
        $result_associado->bindParam(':id', $id);

        if ($result_associado->execute()) {
            // Confirma a transação
            $conn->commit();
            $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Associado apagado com sucesso!</div>"];
        } else {
            // Desfaz a transação em caso de erro
            $conn->rollBack();
            $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Falha ao apagar associado!</div>"];
        }
    } catch (Exception $e) {
        // Desfaz a transação em caso de exceção
        $conn->rollBack();
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: " . $e->getMessage() . "</div>"];
    }
} else {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Nenhum associado encontrado.</div>"];
}

echo json_encode($retorna);
?>
