<?php

// Obtém a URL do banco de dados do Heroku a partir das variáveis de ambiente
$dsn = getenv('JAWSDB_URL');  // A URL completa da conexão

// Decompõe a URL para extrair as partes necessárias
$url = parse_url($dsn);

$host = $url['host'];  // Exemplo: sp6xl8zoyvbumaa2.cbetxkdyhwsb.us-east-1.rds.amazonaws.com
$port = $url['port'];  // Exemplo: 3306
$user = $url['user'];  // Exemplo: wfxjycfz5gav9ou7
$pass = $url['pass'];  // Exemplo: uo6yojfqm9uxo99v
$dbname = ltrim($url['path'], '/');  // Exemplo: q31l3w9zkjcfyxa2 (remover a barra inicial)

// Tenta conectar usando PDO
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    // Caso tenha sucesso na conexão, você pode deixar uma mensagem ou proceder com sua lógica
} catch (PDOException $err) {
    die("Erro: Conexão com banco de dados não realizada com sucesso. Erro gerado: " . $err->getMessage());
}

?>
