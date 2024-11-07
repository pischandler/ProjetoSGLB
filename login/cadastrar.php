<?php
session_start();
ob_start();

// Verifica se o botão de cadastro foi clicado
$btnCadUsuario = filter_input(INPUT_POST, 'btnCadUsuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if($btnCadUsuario) {
    include_once 'conexao.php';
    $dados_rc = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $erro = false;

    // Sanitiza e remove espaços
    $dados_st = array_map('strip_tags', $dados_rc);
    $dados = array_map('trim', $dados_st);

    // Verifica se todos os campos foram preenchidos
    if(in_array('', $dados)) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Necessário preencher todos os campos.</div>";
    } elseif(strlen($dados['senha']) < 6) {
        // Valida o comprimento da senha
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>A senha deve ter no mínimo 6 caracteres.</div>";
    } elseif (stristr($dados['senha'], "'")) {
        // Verifica se há apóstrofos na senha
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>A senha não pode conter apóstrofos.</div>";
    } else {
        // Verifica se o nome de usuário já existe
        $result_usuario = "SELECT id FROM usuarios WHERE usuario='".$dados['usuario']."'";
        $result_usuario = mysqli_query($conn, $result_usuario);
        if(($result_usuario) AND ($result_usuario->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Este usuário já está sendo utilizado.</div>";
        }

        // Verifica se o email já está sendo utilizado
        $result_usuario = "SELECT id FROM usuarios WHERE email='".$dados['email']."'";
        $result_usuario = mysqli_query($conn, $result_usuario);
        if(($result_usuario) AND ($result_usuario->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Este E-mail já está sendo utilizado.</div>";
        }
    }

    // Se não houver erros, realiza o cadastro do usuário
    if(!$erro) {
        // Hash da senha para segurança
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

        // Insere o novo usuário com o tipo 'usuario' e status 'pendente'
        $result_usuario = "INSERT INTO usuarios (nome, sobrenome, email, usuario, senha, tipo, status) VALUES (
            '".$dados['nome']."',
            '".$dados['sobrenome']."',
            '".$dados['email']."',
            '".$dados['usuario']."',
            '".$dados['senha']."',
            'usuario',  -- Tipo padrão 'usuario'
            'pendente'  -- Status inicial 'pendente'
        )";
        $result_usuario = mysqli_query($conn, $result_usuario);

        // Verifica se o cadastro foi bem-sucedido
        if(mysqli_insert_id($conn)) {
            $_SESSION['msgcad'] = "<div class='alert alert-info' role='alert'>Usuário cadastrado com sucesso! Aguarde a autorização do administrador.</div>";
            header("Location: index.php");  // Redireciona para a página de login
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao cadastrar o usuário.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="login.css">
    <link rel="icon" href="../assets/imagemLosBravos.png" type="image/png">
</head>
<body>
    <div class="Login">
        <div class="container">
            <?php
            // Exibe mensagens de erro ou sucesso
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <h4 style="color: #fff;">Cadastro</h4>
            <div class="row mb-3"></div>
            <form method="POST" action="">
                <div class="input-field">
                    <input type="text" placeholder="Digite seu primeiro nome" name="nome" />
                </div>

                <div class="input-field">
                    <input type="text" placeholder="Digite seu sobrenome" name="sobrenome" />
                </div>

                <div class="input-field">
                    <input type="email" placeholder="Digite o seu E-mail" name="email" />
                </div>

                <div class="input-field">
                    <input type="text" placeholder="Digite o usuário" name="usuario" />
                </div>

                <div class="input-field">
                    <input type="password" placeholder="Digite a senha" name="senha" />
                </div>

                <div>
                    <input class="btn btn-primary row mb-2" type="submit" name="btnCadUsuario" value="Salvar"></input>
                    <div>
                        <label>
                            <a href="index.php">Voltar para página de login.</a>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
