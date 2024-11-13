<?php
session_start();
if (empty($_SESSION['id'])) {
  $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário fazer login!</div>";
  header("Location: ../login/");
  exit();
}

// Função para gerar o header com saudação personalizada
function gerarHeader($nomeUsuario)
{
  return "
    <header class='header'>
        <div class='header-left'>         
            <img src='https://losbravosuepg.com.br/assets/logolb.png' height='100' alt='Los Bravos' title='Página Inicial.'>
        </div>
        <h1 id='header-title'>Bem-vindo, " . $_SESSION['nome'] . "</h1>
        <div class='header-right'>
        <a class='btn btn-primary btn-sm' href='../sair.php' role='button'>Sair <i class='fa-solid fa-arrow-right-from-bracket'></i></a>
        </div>
    </header>";
}

$header = gerarHeader($_SESSION['nome']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Los Bravos</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- FontAwesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="style.css">
  
  <style>
    .header {
      width: 100%;
      height: 110px;
      background-color: #d30909;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 20px;
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: top 0.3s ease-in-out;
    }

    .header-left {
      position: absolute;
      left: 20px;
      display: flex;
      align-items: center;
    }

    .header-right {
      position: absolute;
      right: 20px;
      display: flex;
      align-items: center;
    }

    .header-left img {
      margin-left: 10px;
    }

    #header-title {
      margin: 0;
      text-align: center;
      font-size: 2.5rem;
      font-weight: bold;
    }

    .btn-sm {
      margin-left: auto;
      font-size: 1rem;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <?php echo $header; ?>
  
  <div class="Login">
    <div class="game-container">
      <div class="game">
      <div class="message">Ops, parece que a sua solicitação para acesso ainda não foi aceita.</br>Aproveite para se divertir enquanto aguarda!
      </div>
        <div class="start-message">Pressione espaço para começar</div>
        <span class="score"></span>
        <div class="dino"></div>
        <div class="cacto"></div>
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

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Script for game -->
  <script src="script.js"></script>
</body>

</html>
