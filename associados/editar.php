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

} elseif (strlen($dados['celular']) != 15) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Campo celular incorreto!</div>"];

} elseif (empty($dados['modalidades'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário selecionar pelo menos uma modalidade!</div>"];

} elseif (!empty($dados['cep']) && !preg_match("/^\d{5}-\d{3}$/", $dados['cep'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Campo CEP inválido!</div>"];

} else {
    try {
        // Verificar se o CPF já está cadastrado em outro associado
        $query_cpf = "SELECT id FROM associados WHERE cpf = :cpf AND id != :id";
        $stmt_cpf = $conn->prepare($query_cpf);
        $stmt_cpf->bindParam(':cpf', $dados['cpf']);
        $stmt_cpf->bindParam(':id', $dados['id']);
        $stmt_cpf->execute();

        if ($stmt_cpf->rowCount() > 0) {
            // CPF já está sendo usado por outro associado
            $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: CPF já cadastrado por outro associado!</div>"];
        } else {
            // Atualiza as informações do associado
            $query_associado = "UPDATE associados SET nome = :nome, email = :email, cpf = :cpf, genero = :genero, curso = :curso, celular = :celular, cep = :cep, rua = :rua, bairro = :bairro, cidade_id = :cidade_id, numero_resid = :numero_resid, complemento = :complemento, nome_responsavel = :nome_responsavel, telefone_responsavel = :telefone_responsavel WHERE id = :id";
            $edit_associado = $conn->prepare($query_associado);
            $edit_associado->bindParam(':nome', $dados['nome']);
            $edit_associado->bindParam(':email', $dados['email']);
            $edit_associado->bindParam(':id', $dados['id']);
            $edit_associado->bindParam(':cpf', $dados['cpf']);
            $edit_associado->bindParam(':genero', $dados['genero']);
            $edit_associado->bindParam(':celular', $dados['celular']);
            $edit_associado->bindParam(':curso', $dados['curso']);
            $edit_associado->bindParam(':cep', $dados['cep']);
            $edit_associado->bindParam(':bairro', $dados['bairro']);
            $edit_associado->bindParam(':rua', $dados['rua']);
            $edit_associado->bindParam(':numero_resid', $dados['numero_resid']);
            $edit_associado->bindParam(':complemento', $dados['complemento']);
            $edit_associado->bindParam(':nome_responsavel', $dados['nome_responsavel']);
            $edit_associado->bindParam(':telefone_responsavel', $dados['telefone_responsavel']);
            $edit_associado->bindParam(':cidade_id', $dados['cidade']);
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
        }
    } catch (Exception $e) {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: " . $e->getMessage() . "</div>"];
    }
}

echo json_encode($retorna);
?>
