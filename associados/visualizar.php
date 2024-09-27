<?php
include_once "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {

    // Consulta para obter informações do associado, cidade e estado
    $query_associados = "
        SELECT a.id, a.nome, a.email, a.cpf, a.genero, a.celular, a.ddi, a.cep, a.bairro, a.ra, a.rua, a.complemento, a.numero_resid, a.ddi_responsavel, a.telefone_responsavel,
               a.nome_responsavel, a.curso, a.formado, a.ativo, a.cidade_id,
               c.nome AS cidade_nome, e.nome AS estado_nome, e.uf AS estado_uf, c.id AS cidade_id_rec, e.id AS estado_id_rec,
               GROUP_CONCAT(m.nome SEPARATOR ', ') AS modalidades,
               GROUP_CONCAT(m.id SEPARATOR ', ') AS modalidades_id
        FROM associados a
        LEFT JOIN cidades c ON a.cidade_id = c.id
        LEFT JOIN estados e ON c.id_estado = e.id
        INNER JOIN associado_modalidade am ON a.id = am.associado_id
        INNER JOIN modalidade m ON am.modalidade_id = m.id
        WHERE a.id = :id
        GROUP BY a.id
        LIMIT 1
    ";

    $result_associado = $conn->prepare($query_associados);
    $result_associado->bindParam(':id', $id);
    $result_associado->execute();

    $row_associado = $result_associado->fetch(PDO::FETCH_ASSOC);

    if ($row_associado) {
        $retorna = ['erro' => false, 'dados' => $row_associado];
    } else {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
    }
 
} else {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
}

echo json_encode($retorna);
?>
