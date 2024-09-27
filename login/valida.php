<?php
session_start();
include_once("conexao.php");

// Verifica se o botão de login foi clicado
$btnLogin = filter_input(INPUT_POST, 'btnLogin', FILTER_SANITIZE_EMAIL);
if ($btnLogin) {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Verifica se o usuário e a senha não estão vazios
    if (!empty($usuario) && !empty($senha)) {
        // Consulta o usuário no banco de dados
        $result_usuario = "SELECT id, nome, sobrenome, email, senha, status, tipo FROM usuarios WHERE usuario='$usuario' LIMIT 1";
        $resultado_usuario = mysqli_query($conn, $result_usuario);

        // Verifica se a consulta retornou algum resultado
        if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
            $row_usuario = mysqli_fetch_assoc($resultado_usuario);

            // Verifica se a senha está correta
            if (password_verify($senha, $row_usuario['senha'])) {
                // Verifica o status do usuário
                if ($row_usuario['status'] === 'ativo') {
                    // Define as variáveis de sessão
                    $_SESSION['id'] = $row_usuario['id'];
                    $_SESSION['nome'] = $row_usuario['nome'];
                    $_SESSION['sobrenome'] = $row_usuario['sobrenome'];
                    $_SESSION['email'] = $row_usuario['email'];
                    $_SESSION['tipo'] = $row_usuario['tipo'];

                    // Verifica o nível de acesso do usuário
                    if ($row_usuario['tipo'] === 'admin') {
                        // Usuário é administrador, redireciona para página de admin
                        header("Location: ../administrativo");
                    } else {
                        // Usuário comum, redireciona para home
                        header("Location: ../home");
                    }
                } elseif ($row_usuario['status'] === 'pendente') {
                    // Define as variáveis de sessão para usuários pendentes (se necessário)
                    $_SESSION['id'] = $row_usuario['id'];
                    $_SESSION['nome'] = $row_usuario['nome']; // Agora o nome será salvo na sessão
                    $_SESSION['sobrenome'] = $row_usuario['sobrenome'];
                    // Se o status for 'pendente', redireciona para game
                    $_SESSION['msg'] = "<div class='alert alert-warning' role='alert'>Seu cadastro está pendente de aprovação.</div>";
                    header("Location: ../game");
                }
                exit();
            } else {
                // Senha incorreta
                $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Email ou senha incorretos!</div>";
                header("Location: index.php");
            }
        } else {
            // Usuário não encontrado
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Usuário não encontrado!</div>";
            header("Location: index.php");
        }
    } else {
        // Campos de login vazios
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Email ou senha incorretos!</div>";
        header("Location: index.php");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Página não encontrada!</div>";
    header("Location: index.php");
}
