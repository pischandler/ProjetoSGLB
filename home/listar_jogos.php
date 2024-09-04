<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// QUERY para recuperar os eventos, os associados e a modalidade relacionada
$query_events = "
    SELECT j.id, j.title, j.color, j.start, j.end, j.adversario, 
           m.nome AS modalidade,
                      m.id AS modalidade_id,
           GROUP_CONCAT(a.nome ORDER BY a.nome ASC SEPARATOR '\n') AS associados,
                      GROUP_CONCAT(a.id ORDER BY a.id ASC SEPARATOR ',') AS associados_id
    FROM jogos j
    LEFT JOIN associado_jogo aj ON j.id = aj.jogo_id
    LEFT JOIN associados a ON aj.associado_id = a.id
    LEFT JOIN modalidade_jogo mj ON j.id = mj.jogo_id
    LEFT JOIN modalidade m ON mj.modalidade_id = m.id
    GROUP BY j.id
";

// Prepara a QUERY
$result_events = $conn->prepare($query_events);

// Executar a QUERY
$result_events->execute();

// Criar o array que recebe os eventos
$eventos = [];

// Percorrer a lista de registros retornado do banco de dados
while ($row_events = $result_events->fetch(PDO::FETCH_ASSOC)) {

    // Extrair o array
    extract($row_events);

    $eventos[] = [
        'id' => $id,
        'title' => $title,
        'adversario' => $adversario,
        'color' => $color,
        'start' => $start,
        'end' => $end,
        'modalidade' => $modalidade,
        'modalidade_id' => $modalidade_id,
        'associados' => $associados,
        'associados_id' => $associados_id // Adicionar este campo ao array
    ];
}

echo json_encode($eventos);
