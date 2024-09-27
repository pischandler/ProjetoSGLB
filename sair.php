<?php

session_start();
unset($_SESSION['id'], $_SESSION['nome'], $_SESSION['sobrenome'], $_SESSION['email']);

$_SESSION['msg'] = "<div class='alert alert-info' role='alert'>Deslogado com sucesso!</div>";
header("Location: /SGLB/login");