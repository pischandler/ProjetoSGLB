<?php

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

// Receber o id enviado pelo JavaScript
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Acessa o IF quando exite o id do evento
if (!empty($id)) {

    // Iniciar transação
    $conn->beginTransaction();

    try {
        // Apagar relacionamentos na tabela associado_jogo
        $query_apagar_associado_jogo = "DELETE FROM associado_jogo WHERE jogo_id=:id";
        $apagar_associado_jogo = $conn->prepare($query_apagar_associado_jogo);
        $apagar_associado_jogo->bindParam(':id', $id);
        $apagar_associado_jogo->execute();

        // Apagar relacionamentos na tabela modalidade_jogo
        $query_apagar_modalidade_jogo = "DELETE FROM modalidade_jogo WHERE jogo_id=:id";
        $apagar_modalidade_jogo = $conn->prepare($query_apagar_modalidade_jogo);
        $apagar_modalidade_jogo->bindParam(':id', $id);
        $apagar_modalidade_jogo->execute();

        // Apagar evento na tabela jogos
        $query_apagar_event = "DELETE FROM jogos WHERE id=:id";
        $apagar_event = $conn->prepare($query_apagar_event);
        $apagar_event->bindParam(':id', $id);
        $apagar_event->execute();

        // Confirmar transação
        $conn->commit();

        $retorna = ['status' => true, 'msg' => 'Evento apagado com sucesso!'];

    } catch (Exception $e) {
        // Reverter transação em caso de erro
        $conn->rollBack();
        $retorna = ['status' => false, 'msg' => 'Erro: Evento não apagado! ' . $e->getMessage()];
    }

} else { // Acessa o ELSE quando o id está vazio
    $retorna = ['status' => false, 'msg' => 'Erro: Necessário enviar o id do evento!'];
}

// Converter o array em objeto e retornar para o JavaScript
echo json_encode($retorna);

?>
