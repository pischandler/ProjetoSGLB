<?php

include_once "conexao.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['id'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Tente novamente mais tarde.</div>"];

} elseif (empty($dados['nome'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>"];

} elseif (empty($dados['email'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!</div>"];

} elseif (strlen($dados['cpf']) != 11) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Campo CPF incorreto!</div>"];

} else {
    try {
        // Atualiza as informações do associado
        $query_associado = "UPDATE associados SET nome = :nome, email = :email, cpf = :cpf, genero = :genero WHERE id = :id";
        $edit_associado = $conn->prepare($query_associado);
        $edit_associado->bindParam(':nome', $dados['nome']);
        $edit_associado->bindParam(':email', $dados['email']);
        $edit_associado->bindParam(':id', $dados['id']);
        $edit_associado->bindParam(':cpf', $dados['cpf']);
        $edit_associado->bindParam(':genero', $dados['genero']);
        $edit_associado->execute();

        // Remove modalidades antigas
        $query_delete_modalidades = "DELETE FROM associado_modalidade WHERE associado_id = :id";
        $delete_modalidades = $conn->prepare($query_delete_modalidades);
        $delete_modalidades->bindParam(':id', $dados['id']);
        $delete_modalidades->execute();

        // Insere as novas modalidades associadas
        if (!empty($dados['modalidades'])) {
            $query_modalidades = "INSERT INTO associado_modalidade (associado_id, modalidade_id) VALUES (:associado_id, :modalidade_id)";
            $cad_modalidades = $conn->prepare($query_modalidades);

            foreach ($dados['modalidades'] as $modalidade_id) {
                $cad_modalidades->bindParam(':associado_id', $dados['id']);
                $cad_modalidades->bindParam(':modalidade_id', $modalidade_id);
                $cad_modalidades->execute();
            }
        }

        $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Associado editado com sucesso!</div>"];

    } catch (Exception $e) {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: " . $e->getMessage() . "</div>"];
    }
}

echo json_encode($retorna);
?>
