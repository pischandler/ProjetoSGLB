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
            <button class='btn btn-primary' id='sidebar_button' type='button' data-bs-toggle='offcanvas' data-bs-target='#offcanvasWithBothOptions' aria-controls='offcanvasWithBothOptions'>
                <i class='fa-solid fa-bars'></i>
            </button>
            <a href='/SGLB/home'>
                <img src='https://losbravosuepg.com.br/assets/logolb.png' height='100' alt='Los Bravos' title='Página Inicial.'>
            </a>
        </div>
        <h1 id='header-title'></h1>
    </header>";
}

$header = gerarHeader($_SESSION['nome']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Select2 Bootstrap Theme -->
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/5414af6fb5.js" crossorigin="anonymous"></script>
  <link rel="icon" href="../assets/imagemLosBravos.png" type="image/png">


  <title>Painel Administrativo</title>

  <style>
    .offcanvas-title {
      color: aliceblue;
    }

    .header {
      width: 100%;
      height: 110px;
      background-color: #d30909;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      /* Centraliza os itens do header */
      padding: 0 20px;
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: top 0.3s ease-in-out;
    }

    .header-left {
      position: absolute;
      /* Posiciona o logo à esquerda */
      left: 20px;
      /* Ajusta a distância da margem esquerda */
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

    .sidebar {
      background-color: #d30909;
    }

    .list-group-item {
      background-color: transparent;
      color: #c2c2c2;
      border: none;
    }

    .list-group-item:hover,
    .list-group-item:focus {
      background-color: #ee3a34;
      color: #ffffff;
    }

    .list-group-item i {
      margin-right: 10px;
    }

    #sidebar_button {
      background-color: #ee3a34;
      border-color: #ee3a34;
    }

    .highlight-home {
      color: #1b98e0 !important;
    }
  </style>
</head>

<body>
  <!-- Inserir o header gerado pela função -->
  <?php echo $header; ?>

  <!-- Inserir o sidebar -->
  <?php include '../includes/sidebar.php'; ?>

  <div class="container">
    <div class="row mt-4">
    <span id="msgAlerta"></span>
        <div class="col-lg-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 style="display: inline; cursor: pointer; color: inherit;">
                    Usuários Ativos
                </h4>
                <h4 style="display: inline; cursor: pointer; color: inherit; margin-left: 20px;">
                    Pendentes
                </h4>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <div id="usuarios-ativos">
                <span class="listar-usuarios-ativos"></span>
            </div>
            <div id="solicitacoes" style="display: none;">
                <h4>Solicitações</h4>
                <span class="listar-solicitacoes"></span>
            </div>
        </div>
    </div>
</div>


  <!-- Scripts -->
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <script src="js/custom.js"></script>

  <script>
    // Função para definir o título e a mensagem de boas-vindas
    function setHeaderContent(title) {
      if (title) {
        document.getElementById('header-title').textContent = title;
      } else {
        document.getElementById('header-title').style.display = 'none';
      }
    }

    // Exemplo de uso:
    setHeaderContent("Painel Administrativo");
  </script>
</body>

</html>