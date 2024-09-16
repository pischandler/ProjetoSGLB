<?php

include_once "conexao.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['nome'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>"];
} elseif (empty($dados['email'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!</div>"];
} elseif (strlen($dados['cpf']) != 11) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Campo CPF incorreto!</div>"];
} elseif (strlen($dados['celular']) != 15) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Campo celular incorreto!</div>"];
} elseif (empty($dados['modalidades'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário selecionar pelo menos uma modalidade!</div>"];
} elseif (empty($dados['curso'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário selecionar o curso!</div>"];
} elseif (!empty($dados['cep']) && !preg_match("/^\d{5}-\d{3}$/", $dados['cep'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Campo CEP inválido!</div>"];
} else {
    try {
        // Verificar se o CPF já está cadastrado
        $query_cpf = "SELECT id FROM associados WHERE cpf = :cpf";
        $stmt_cpf = $conn->prepare($query_cpf);
        $stmt_cpf->bindParam(':cpf', $dados['cpf']);
        $stmt_cpf->execute();

        if ($stmt_cpf->rowCount() > 0) {
            $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: CPF já cadastrado!</div>"];
        } else {
            // CPF não está cadastrado, pode continuar o cadastro
            $query_associados = "INSERT INTO associados (nome, email, cpf, genero, celular, curso, cep, bairro, rua, numero_resid, complemento, nome_responsavel, telefone_responsavel, cidade_id) 
                                 VALUES (:nome, :email, :cpf, :genero, :celular, :curso, :cep, :bairro, :rua, :numero_resid, :complemento, :nome_responsavel, :telefone_responsavel, :cidade_id)";
            $cad_associado = $conn->prepare($query_associados);
            $cad_associado->bindParam(':nome', $dados['nome']);
            $cad_associado->bindParam(':email', $dados['email']);
            $cad_associado->bindParam(':cpf', $dados['cpf']);
            $cad_associado->bindParam(':genero', $dados['genero']);
            $cad_associado->bindParam(':celular', $dados['celular']);
            $cad_associado->bindParam(':cep', $dados['cep']);
            $cad_associado->bindParam(':bairro', $dados['bairro']);
            $cad_associado->bindParam(':rua', $dados['rua']);
            $cad_associado->bindParam(':numero_resid', $dados['numero_resid']);
            $cad_associado->bindParam(':complemento', $dados['complemento']);
            $cad_associado->bindParam(':nome_responsavel', $dados['nome_responsavel']);
            $cad_associado->bindParam(':telefone_responsavel', $dados['telefone_responsavel']);
            $cad_associado->bindParam(':curso', $dados['curso']);
            $cad_associado->bindParam(':cidade_id', $dados['cidade']);
            $cad_associado->execute();

            $associado_id = $conn->lastInsertId();

            // Insere as modalidades associadas
            $query_modalidades = "INSERT INTO associado_modalidade (associado_id, modalidade_id) VALUES (:associado_id, :modalidade_id)";
            $cad_modalidades = $conn->prepare($query_modalidades);

            foreach ($dados['modalidades'] as $modalidade_id) {
                $cad_modalidades->bindParam(':associado_id', $associado_id);
                $cad_modalidades->bindParam(':modalidade_id', $modalidade_id);
                $cad_modalidades->execute();
            }

            $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Associado cadastrado com sucesso!</div>"];
        }
    } catch (Exception $e) {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: " . $e->getMessage() . "</div>"];
    }
}

echo json_encode($retorna);
?>
