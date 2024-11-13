<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Esqueceu sua Senha</title>
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
      <h4 style="color: #fff;">Esqueci Minha Senha</h4>
      <div class="row mb-4"></div>
      <h5 style="color: #fff;">Envie um e-mail para [endereço de e-mail de suporte] informando que esqueceu sua senha e incluía o e-mail que você utilizou no cadastro do sistema.</h5>
      <div class="row mb-4"></div>
      <div>
        <label>
          <a href="index.php">Voltar para página de login.</a>
        </label>
      </div>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src='js/custom.js'></script>
</body>

</html>