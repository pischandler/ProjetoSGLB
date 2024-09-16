<?php
// Mostrar erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber os dados enviados pelo JavaScript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Validação dos campos existentes
if (empty($dados['cad_title'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Categoria.']);
    exit;
}

if (empty($dados['cad_adversario'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Adversário']);
    exit;
}
if (empty($dados['cad_genero'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Gênero']);
    exit;
}
if (empty($dados['cad_modalidade'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário selecionar a Modalidade']);
    exit;
}
if (empty($dados['cad_cidade'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário selecionar a Cidade']);
    exit;
}

// Validação dos novos campos

// Verificar se o campo Local está preenchido
if (empty($dados['cad_local'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Local']);
    exit;
}

// Verificar se o campo Rua está preenchido
if (empty($dados['cad_rua'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Rua']);
    exit;
}

// Verificar se o campo Bairro está preenchido
if (empty($dados['cad_bairro'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Bairro']);
    exit;
}

// Verificar se o campo Número está preenchido
if (empty($dados['cad_numero'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo Número']);
    exit;
}

// Verificar se o CEP está preenchido e se tem o formato correto
if (empty($dados['cad_cep'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: Necessário preencher o campo CEP']);
    exit;
}if (!preg_match('/^\d{5}-?\d{3}$/', $dados['cad_cep'])) {
    echo json_encode(['status' => false, 'msg' => 'Erro: O CEP deve conter 8 dígitos numéricos, com ou sem hífen.']);
    exit;
}

try {
    // Criar a QUERY para cadastrar evento no banco de dados, agora incluindo os novos campos
    $query_cad_event = "INSERT INTO jogos (title, color, start, end, adversario, placar_casa, placar_adversario, genero, cidade_id, rua, local, cep, bairro, numero, complemento) 
                        VALUES (:title, :color, :start, :end, :adversario, :placar_casa, :placar_adversario, :genero, :cidade_id, :rua, :local, :cep, :bairro, :numero, :complemento)";
    
    $cad_event = $conn->prepare($query_cad_event);

    // Substituir o link pelo valor
    $cad_event->bindParam(':title', $dados['cad_title']);
    $cad_event->bindParam(':color', $dados['cad_color']);
    $cad_event->bindParam(':start', $dados['cad_start']);
    $cad_event->bindParam(':end', $dados['cad_end']);
    $cad_event->bindParam(':genero', $dados['cad_genero']);
    $cad_event->bindParam(':adversario', $dados['cad_adversario']);
    $cad_event->bindParam(':placar_casa', $dados['cad_placar_casa']);
    $cad_event->bindParam(':placar_adversario', $dados['cad_placar_adversario']);
    $cad_event->bindParam(':cidade_id', $dados['cad_cidade']);

    // Bind dos novos campos
    $cad_event->bindParam(':rua', $dados['cad_rua']);
    $cad_event->bindParam(':local', $dados['cad_local']);
    $cad_event->bindParam(':cep', $dados['cad_cep']);
    $cad_event->bindParam(':bairro', $dados['cad_bairro']);
    $cad_event->bindParam(':numero', $dados['cad_numero']);
    $cad_event->bindParam(':complemento', $dados['cad_complemento']);

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
        $associados = [];
        $associados_ids = [];
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
                $associados_ids[] = $associado_id;
            }
        }

        // Consultar o nome da modalidade
        $query_modalidade_nome = "SELECT nome FROM modalidade WHERE id = :modalidade_id";
        $stmt_modalidade_nome = $conn->prepare($query_modalidade_nome);
        $stmt_modalidade_nome->bindParam(':modalidade_id', $dados['cad_modalidade']);
        $stmt_modalidade_nome->execute();
        $modalidade = $stmt_modalidade_nome->fetchColumn();

        // Consulta para obter os nomes da cidade e do estado
        $query_info_local = "SELECT c.nome AS cidade_nome, es.nome AS estado_nome, es.uf AS estado_uf
                             FROM cidades c
                             LEFT JOIN estados es ON c.id_estado = es.id
                             WHERE c.id = :cidade_id";
        $info_local = $conn->prepare($query_info_local);
        $info_local->bindParam(':cidade_id', $dados['cad_cidade']);
        $info_local->execute();
        $local = $info_local->fetch(PDO::FETCH_ASSOC);

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
            'genero' => $dados['cad_genero'],
            'placar_adversario' => $dados['cad_placar_adversario'],
            'placar_casa' => $dados['cad_placar_casa'],
            'modalidade' => $modalidade,
            'modalidade_id' => strval($dados['cad_modalidade']),
            'associados' => implode("\n", $associados),
            'associados_id' => implode(',', $associados_ids),
            'cidade_nome' => $local['cidade_nome'] ?? '',
            'estado_nome' => $local['estado_nome'] ?? '',
            'estado_uf' => $local['estado_uf'] ?? '',
            // Incluindo os novos campos na resposta
            'rua' => $dados['cad_rua'],
            'local' => $dados['cad_local'],
            'cep' => $dados['cad_cep'],
            'bairro' => $dados['cad_bairro'],
            'numero' => $dados['cad_numero'],
            'complemento' => $dados['cad_complemento']
        ];
    } else {
        $retorna = ['status' => false, 'msg' => 'Erro: Evento não cadastrado!'];
    }
} catch (PDOException $e) {
    $retorna = ['status' => false, 'msg' => 'Erro: ' . $e->getMessage()];
}

echo json_encode($retorna);
