<?php

// Obtém a URL de conexão do banco de dados do Heroku a partir da variável de ambiente
$dsn = getenv('JAWSDB_URL');  // A URL completa da conexão

// Decompõe a URL para extrair as partes necessárias
$url = parse_url($dsn);

$host = $url['host'];  // Exemplo: sp6xl8zoyvbumaa2.cbetxkdyhwsb.us-east-1.rds.amazonaws.com
$port = $url['port'];  // Exemplo: 3306
$user = $url['user'];  // Exemplo: wfxjycfz5gav9ou7
$pass = $url['pass'];  // Exemplo: uo6yojfqm9uxo99v
$dbname = ltrim($url['path'], '/');  // Exemplo: q31l3w9zkjcfyxa2 (remove a barra inicial)

// Tenta conectar ao banco de dados usando mysqli
$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

// Verifica se a conexão falhou
if (!$conn) {
    die("Erro: Falha na conexão com o banco de dados. " . mysqli_connect_error());
}

?>
