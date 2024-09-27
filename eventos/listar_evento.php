<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Configurar o tipo de conteúdo para JSON
header('Content-Type: application/json');

// QUERY para recuperar os eventos com os novos campos
$query_events = "SELECT e.id, e.title, e.color, e.start, e.cidade_id, e.end, e.cep, e.rua, e.local, e.bairro, e.numero, e.complemento, 
                 c.nome AS cidade_nome, es.nome AS estado_nome, es.uf AS estado_uf, es.id AS id_estado, c.id AS id_cidade
                 FROM events e
                 LEFT JOIN cidades c ON e.cidade_id = c.id
                 LEFT JOIN estados es ON c.id_estado = es.id;";

// Prepara a QUERY
$result_events = $conn->prepare($query_events);

// Executar a QUERY
$result_events->execute();

// Criar o array que recebe os eventos
$eventos = [];

// Percorrer a lista de registros retornado do banco de dados
while($row_events = $result_events->fetch(PDO::FETCH_ASSOC)){
    // Extrair o array
    extract($row_events);

    $eventos[] = [
        'id' => $id,
        'title' => $title,
        'color' => $color,
        'start' => $start,
        'end' => $end,
        'cidade_nome' => $cidade_nome,
        'estado_nome'=> $estado_nome,
        'estado_uf'=> $estado_uf,
        'id_estado' => $id_estado,
        'id_cidade' => $id_cidade,
        'cep' => $cep,
        'rua' => $rua,
        'local' => $local,
        'bairro' => $bairro,
        'numero' => $numero,
        'complemento' => $complemento
    ];
}

// Retornar os dados em formato JSON
echo json_encode($eventos, JSON_PRETTY_PRINT);

?>
