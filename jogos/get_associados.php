<?php
include_once './conexao.php';

try {
    // Verificar se o parâmetro 'genero' e 'modalidade' estão presentes na requisição
    $genero = isset($_GET['genero']) ? $_GET['genero'] : '';
    $modalidade = isset($_GET['modalidade']) ? $_GET['modalidade'] : '';

    // Criar a query inicial com ORDER BY nome ASC para ordenação alfabética
    $query = "SELECT associados.id, associados.nome 
              FROM associados 
              JOIN associado_modalidade ON associados.id = associado_modalidade.associado_id";
              
    $conditions = [];

    // Adicionar condições baseadas nos filtros de gênero e modalidade
    if ($genero) {
        $conditions[] = "associados.genero = :genero";
    }
    if ($modalidade) {
        $conditions[] = "associado_modalidade.modalidade_id = :modalidade";
    }

    // Adicionar as condições, se houver, na consulta
    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Adicionar a ordenação alfabética
    $query .= " ORDER BY associados.nome ASC";

    $stmt = $conn->prepare($query);

    // Bind dos parâmetros
    if ($genero) {
        $stmt->bindParam(':genero', $genero);
    }
    if ($modalidade) {
        $stmt->bindParam(':modalidade', $modalidade);
    }

    // Executar a consulta e obter os resultados
    $stmt->execute();
    $associados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar os resultados como JSON
    echo json_encode($associados);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
}
?>
