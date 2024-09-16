<?php
include_once "conexao.php";

$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_NUMBER_INT);
$searchTerm = filter_input(INPUT_GET, "searchTerm", FILTER_SANITIZE_SPECIAL_CHARS);

if (!empty($pagina)) {

    // Calcular o início da visualização
    $qnt_result_pg = 40; // Quantidade de registros por página
    $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

    // Adicionar o termo de pesquisa na consulta
    $searchCondition = !empty($searchTerm) ? "WHERE a.nome LIKE :searchTerm OR a.email LIKE :searchTerm" : "";

    // Consulta SQL ajustada para incluir as modalidades
    $query_associados = "
        SELECT a.id, a.nome, a.email, a.curso, GROUP_CONCAT(m.nome SEPARATOR ', ') AS modalidades
        FROM associados a
        LEFT JOIN associado_modalidade am ON a.id = am.associado_id
        LEFT JOIN modalidade m ON am.modalidade_id = m.id
        $searchCondition
        GROUP BY a.id
        ORDER BY a.id DESC 
        LIMIT $inicio, $qnt_result_pg";

    $result_associados = $conn->prepare($query_associados);

    // Bind do termo de pesquisa se ele estiver definido
    if (!empty($searchTerm)) {
        $searchTerm = "%$searchTerm%";
        $result_associados->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    }

    $result_associados->execute();

    $dados = "<div class='table-responsive'>
        <table class='table table-striped table-bordered'>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Curso</th>
                    <th>E-mail</th>
                    <th>Modalidades</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>";

    while ($row_associado = $result_associados->fetch(PDO::FETCH_ASSOC)) {
        extract($row_associado);
        $dados .= "<tr>
                    <td>$nome</td>
                    <td>$curso</td>
                    <td>$email</td>
                    <td>$modalidades</td>
                    <td>
                        <button id='$id' class='btn btn-outline-primary btn-sm' onclick='visAssociado($id)'><i class='fa-regular fa-eye'></i></button>
                        <button id='$id' class='btn btn-outline-warning btn-sm' onclick='editAssociadoDados($id)'><i class='fa-regular fa-pen-to-square'></i></button>
                        <button id='$id' class='btn btn-outline-danger btn-sm' onclick='apagarAssociadoDados($id)'><i class='fa-solid fa-trash'></i></button>
                    </td>
                </tr>";
    }

    $dados .= "</tbody>
        </table>
    </div>";

    // Paginação - Somar a quantidade de usuários
    $query_pg = "SELECT COUNT(a.id) AS num_result FROM associados a $searchCondition";
    $result_pg = $conn->prepare($query_pg);

    // Bind do termo de pesquisa se ele estiver definido
    if (!empty($searchTerm)) {
        $result_pg->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    }

    $result_pg->execute();
    $row_pg = $result_pg->fetch(PDO::FETCH_ASSOC);

    // Quantidade de páginas
    $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

    $max_links = 2;

    $dados .= '<nav aria-label="Page navigation example"><ul class="pagination pagination-sm justify-content-center">';

    $dados .= "<li class='page-item'><a href='#' class='page-link' onclick='listarAssociados(1, \"$searchTerm\")'>Primeira</a></li>";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $dados .= "<li class='page-item'><a class='page-link' href='#' onclick='listarAssociados($pag_ant, \"$searchTerm\")'>$pag_ant</a></li>";
        }
    }

    $dados .= "<li class='page-item active'><a class='page-link' href='#'>$pagina</a></li>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $dados .= "<li class='page-item'><a class='page-link' href='#' onclick='listarAssociados($pag_dep, \"$searchTerm\")'>$pag_dep</a></li>";
        }
    }

    $dados .= "<li class='page-item'><a class='page-link' href='#' onclick='listarAssociados($quantidade_pg, \"$searchTerm\")'>Última</a></li>";
    $dados .=   '</ul></nav>';

    echo $dados;
} else {
    echo "<div class='alert alert-danger' role='alert'>Erro: Nenhum associado encontrado!</div>";
}
