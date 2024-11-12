<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login.css">
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
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
      if (isset($_SESSION['msgcad'])) {
        echo $_SESSION['msgcad'];
        unset($_SESSION['msgcad']);
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
            type="text"
            placeholder="Usuário"
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
              <a href="cadastrar.php"></br>Crie uma conta.</a>
            </label>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Help Icon -->
  <div class="help-icon" id="helpIcon">
    <i class="bi bi-question-circle-fill"></i>
  </div>
  <!-- Help Modal -->
  <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="helpModalLabel">Ajuda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="helpContent">
          <!-- Help content will be dynamically inserted here -->
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src='js/custom.js'></script>

</body>


</html>