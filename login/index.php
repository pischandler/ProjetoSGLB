<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <title>Index Page</title>
  <link rel="stylesheet" href="css/custom.css">
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
          <h5 class="modal-title" id="helpModalLabel">Help</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="helpContent">
          <!-- Help content will be dynamically inserted here -->
        </div>
      </div>
    </div>
  </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>

</body>


</html>