<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <title>Login</title>
  <link rel="stylesheet" href="Login.css">
  <link rel="icon" href="../assets/imagemLosBravos.png" type="image/png">
</head>

<body>
  <div class="Login">
    <div class="container">
      <?php
      if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
      }
      ?>
      <div class="row mb-3"></div>
      <div class="imagem">
        <img
          src="https://losbravosuepg.com.br/assets/logolb.png"
          width=""
          height="250"
          alt="Los Bravos"
          title="Los Bravos" />
      </div>
      <form method="POST" action="valida.php">
        <div class="input-field">
          <input
            type="email"
            placeholder="E-mail"
            name="usuario" />
        </div>
        <div class="input-field">
          <input
            type="password"
            placeholder="Senha"
            name="senha" />
        </div>
        <div>
          <div class="recall-forget row mb-1">
            <label>
              <input type="checkbox" />
              Lembre de mim.
            </label>
          </div>
          <input class="btn btn-primary row mb-2" type="submit" name="btnLogin" value="Acessar"></input>
          <div>
            <label>
              <a href="#">Esqueceu sua senha?</a>
            </label>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>

</html>