<?php
// Mostrar erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber os dados enviados pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

try {
    // Criar a QUERY para cadastrar evento no banco de dados
    $query_cad_event = "INSERT INTO jogos (title, color, start, end, adversario) VALUES (:title, :color, :start, :end, :adversario)";
    $cad_event = $conn->prepare($query_cad_event);

    // Substituir o link pelo valor
    $cad_event->bindParam(':title', $dados['cad_title']);
    $cad_event->bindParam(':color', $dados['cad_color']);
    $cad_event->bindParam(':start', $dados['cad_start']);
    $cad_event->bindParam(':end', $dados['cad_end']);
    $cad_event->bindParam(':adversario', $dados['cad_adversario']);

    // Executar a query
    if ($cad_event->execute()) {
        $evento_id = $conn->lastInsertId();

        // Cadastrar a modalidade associada ao evento
        $query_cad_modalidade_jogo = "INSERT INTO modalidade_jogo (modalidade_id, jogo_id) VALUES (:modalidade_id, :jogo_id)";
        $cad_modalidade_jogo = $conn->prepare($query_cad_modalidade_jogo);
        $cad_modalidade_jogo->bindParam(':modalidade_id', $dados['cad_modalidade']);
        $cad_modalidade_jogo->bindParam(':jogo_id', $evento_id);
        $cad_modalidade_jogo->execute();

        // Inserir associados relacionados ao evento
        $associados = []; // Cria um array vazio para armazenar os nomes dos associados
        $associados_ids = []; // Cria um array para armazenar os IDs dos associados
        if (!empty($dados['associados'])) {
            foreach ($dados['associados'] as $associado_id) {
                $query_associado_jogo = "INSERT INTO associado_jogo (associado_id, jogo_id) VALUES (:associado_id, :jogo_id)";
                $cad_associado_jogo = $conn->prepare($query_associado_jogo);
                $cad_associado_jogo->bindParam(':associado_id', $associado_id);
                $cad_associado_jogo->bindParam(':jogo_id', $evento_id);
                $cad_associado_jogo->execute();

                // Obter o nome do associado e adicionar ao array $associados
                $query_associado_nome = "SELECT nome FROM associados WHERE id = :associado_id";
                $stmt_associado_nome = $conn->prepare($query_associado_nome);
                $stmt_associado_nome->bindParam(':associado_id', $associado_id);
                $stmt_associado_nome->execute();
                $nome_associado = $stmt_associado_nome->fetchColumn();
                $associados[] = $nome_associado;
                $associados_ids[] = $associado_id; // Armazena o ID do associado
            }
        }

        // Consultar o nome da modalidade
        $query_modalidade_nome = "SELECT nome FROM modalidade WHERE id = :modalidade_id";
        $stmt_modalidade_nome = $conn->prepare($query_modalidade_nome);
        $stmt_modalidade_nome->bindParam(':modalidade_id', $dados['cad_modalidade']);
        $stmt_modalidade_nome->execute();
        $modalidade = $stmt_modalidade_nome->fetchColumn();
        
        // Preparar a resposta
        $retorna = [
            'status' => true,
            'msg' => 'Evento cadastrado com sucesso!',
            'id' => $evento_id,
            'title' => $dados['cad_title'],
            'adversario' => $dados['cad_adversario'],
            'color' => $dados['cad_color'],
            'start' => $dados['cad_start'],
            'end' => $dados['cad_end'],
            'modalidade' => $modalidade, // Inclui o nome da modalidade
            'modalidade_id' => strval($dados['cad_modalidade']), // Inclui o ID da modalidade como string
            'associados' => implode("\n", $associados), // Inclui o nome dos associados com quebras de linha
            'associados_id' => implode(',', $associados_ids) // Inclui os IDs dos associados separados por vírgulas
        ];
    } else {
        $retorna = ['status' => false, 'msg' => 'Erro: Evento não cadastrado!'];
    }
} catch (PDOException $e) {
    $retorna = ['status' => false, 'msg' => 'Erro: ' . $e->getMessage()];
}

echo json_encode($retorna);
