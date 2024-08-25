<?php
session_start();
include_once("conexao.php");
$btnLogin = filter_input(INPUT_POST, 'btnLogin', FILTER_SANITIZE_EMAIL);
if($btnLogin) {
  $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_EMAIL);
  $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  //echo "$usuario - $senha";
  if((!empty($usuario)) AND (!empty($senha))) {
    //gerar senha criptografada
    //echo password_hash($senha, PASSWORD_DEFAULT);
    //pesquisar o usuario no BD
    $result_usuario = "SELECT id, nome, email, senha FROM usuarios WHERE usuario='$usuario' LIMIT 1";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    if($resultado_usuario) {
      $row_usuario = mysqli_fetch_assoc($resultado_usuario);
      if(password_verify($senha, $row_usuario['senha'])) {
        $_SESSION['id'] = $row_usuario['id'];
        $_SESSION['nome'] = $row_usuario['nome'];
        $_SESSION['email'] = $row_usuario['email'];
          header("Location: ../home");
      }else {
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Email ou senha incorretos!</div>";
        header("Location: index.php");
      }
    }
  }else {
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Email ou senha incorretos!</div>";
  header("Location: index.php");
  }
}else{
  $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Página não encontrada!</div>";
  header("Location: index.php");
}