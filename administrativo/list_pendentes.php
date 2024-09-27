<?php
include_once "conexao.php";

$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_NUMBER_INT);

if (!empty($pagina)) {

  // Calcular o início da visualização
  $qnt_result_pg = 40; // Quantidade de registros por página
  $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

  // Modifique a consulta para filtrar apenas usuários com status "ativo"
  $query_usuarios = "SELECT id, nome, email, sobrenome, tipo, status FROM usuarios WHERE status = 'pendente' ORDER BY id DESC LIMIT $inicio, $qnt_result_pg";
  $result_usuarios = $conn->prepare($query_usuarios);
  $result_usuarios->execute();

  $dados = "<div class='table-responsive'>
            <table class='table table-striped table-bordered'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>";

  // Verifica se há usuários pendente
  if ($result_usuarios->rowCount() > 0) {
    while ($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)) {
      extract($row_usuario);
      $dados .= "<tr>
                        <td>$id</td>
                        <td>$nome $sobrenome</td>
                        <td>$email</td>
                        <td>   
                        <button id='$id' class='btn btn-outline-warning btn-sm' onclick='aceitarUsuarioPendente($id)'><i class='fa-solid fa-check'></i></button>

                        <button id='$id' class='btn btn-outline-danger btn-sm' onclick='apagarUsuarioDados($id)'><i class='fa-solid fa-x'></i></button>
                        </td>
                    </tr>";
    }
  } else {
    $dados .= "<tr><td colspan='4'>Nenhum usuário pendente encontrado.</td></tr>";
  }

  $dados .= "</tbody>
        </table>
    </div>";

  // Paginação - Somar a quantidade de usuários ativos
  $query_pg = "SELECT COUNT(id) AS num_result FROM usuarios WHERE status = 'pendente'";
  $result_pg = $conn->prepare($query_pg);
  $result_pg->execute();
  $row_pg = $result_pg->fetch(PDO::FETCH_ASSOC);

  // Quantidade de páginas
  $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

  $max_links = 2;

  $dados .= '<nav aria-label="Page navigation example"><ul class="pagination pagination-sm justify-content-center">';

  $dados .= "<li class='page-item'><a href='#' class='page-link' onclick='listarUsuarios(1)'>Primeira</a></li>";

  for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
    if ($pag_ant >= 1) {
      $dados .= "<li class='page-item'><a class='page-link' href='#' onclick='listarUsuarios($pag_ant)'>$pag_ant</a></li>";
    }
  }

  $dados .= "<li class='page-item active'><a class='page-link' href='#'>$pagina</a></li>";

  for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
    if ($pag_dep <= $quantidade_pg) {
      $dados .= "<li class='page-item'><a class='page-link' href='#' onclick='listarUsuarios($pag_dep)'>$pag_dep</a></li>";
    }
  }

  $dados .= "<li class='page-item'><a class='page-link' href='#' onclick='listarUsuarios($quantidade_pg)'>Última</a></li>";
  $dados .= '</ul></nav>';

  echo $dados;
} else {
  echo "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>";
}
