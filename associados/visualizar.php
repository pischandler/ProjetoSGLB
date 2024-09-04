<?php
include_once "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {

    // Consulta para obter informações do associado e suas modalidades
  /*  $query_associados = "
    SELECT a.id, a.nome, a.email, a.cpf, a.genero, GROUP_CONCAT(m.id SEPARATOR ',') AS modalidades
    FROM associados a
    INNER JOIN associado_modalidade am ON a.id = am.associado_id
    INNER JOIN modalidade m ON am.modalidade_id = m.id
    WHERE a.id = :id
    GROUP BY a.id
    LIMIT 1
"; */ 

   $query_associados = "
        SELECT a.id, a.nome, a.email, a.cpf, a.genero, GROUP_CONCAT(m.nome SEPARATOR ', ') AS modalidades,
        GROUP_CONCAT(m.id SEPARATOR ', ') AS modalidades_id
        FROM associados a
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
