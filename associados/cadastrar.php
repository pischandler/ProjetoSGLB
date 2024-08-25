<?php

include_once "conexao.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['nome'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>"];
} elseif (empty($dados['email'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!</div>"];
} elseif (strlen($dados['cpf']) != 11) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Campo CPF incorreto!</div>"];
} elseif (empty($dados['modalidades'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário selecionar pelo menos uma modalidade!</div>"];
} else {
    try {
        // Insere o associado
        $query_associados = "INSERT INTO associados (nome, email, cpf, genero) VALUES (:nome, :email, :cpf, :genero)";
        $cad_associado = $conn->prepare($query_associados);
        $cad_associado->bindParam(':nome', $dados['nome']);
        $cad_associado->bindParam(':email', $dados['email']);
        $cad_associado->bindParam(':cpf', $dados['cpf']);
        $cad_associado->bindParam(':genero', $dados['genero']);
        $cad_associado->execute();

        $associado_id = $conn->lastInsertId(); // Obtém o ID do último associado inserido

        // Insere as modalidades associadas
        $query_modalidades = "INSERT INTO associado_modalidade (associado_id, modalidade_id) VALUES (:associado_id, :modalidade_id)";
        $cad_modalidades = $conn->prepare($query_modalidades);

        foreach ($dados['modalidades'] as $modalidade_id) {
            $cad_modalidades->bindParam(':associado_id', $associado_id);
            $cad_modalidades->bindParam(':modalidade_id', $modalidade_id);
            $cad_modalidades->execute();
        }

        $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Associado cadastrado com sucesso!</div>"];
    } catch (Exception $e) {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: " . $e->getMessage() . "</div>"];
    }
}

echo json_encode($retorna);
?>
