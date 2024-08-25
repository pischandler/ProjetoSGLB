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
        <h1 id='header-title'>Bem-vindo, " . htmlspecialchars($nomeUsuario) . "</h1>
        <a class='btn btn-secondary btn-sm' href='../login/' role='button'>Sair <i class='fa-solid fa-arrow-right-from-bracket'></i></a>
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
  
  <!-- Custom CSS -->
  <link href="css/custom.css" rel="stylesheet">
  
  <title>Página Inicial</title>
  
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
      justify-content: space-between;
      padding: 20px;
    }

    .header-left {
      display: flex;
      align-items: center;
    }

    .header-left img {
      margin-left: 10px;
    }

    #header-title {
      margin: 0;
      flex-grow: 1;
      text-align: center;
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

  <!-- Conteúdo principal -->
  <div class="container">
    <div class="row mt-4">
      <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <input type="text" id="searchTerm" class="form-control me-2" placeholder="Pesquisar" />
        <div>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadAssociadoModal">Cadastrar</button>
        </div>
      </div>
    </div>
    <hr>
    <span id="msgAlerta"></span>
    <div class="row">
      <div class="col-lg-12">
        <span class="listar-associados"></span>
      </div>
    </div>
  </div>

  <!-- Modais -->
  <!-- Modal de Cadastro -->
  <div class="modal fade" id="cadAssociadoModal" tabindex="-1" aria-labelledby="cadAssociadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cadAssociadoModalLabel">Cadastrar Associado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="cad-associado-form">
            <span id="msgAlertaErroCad"></span>
            <div class="mb-3">
              <label for="nome" class="col-form-label">Nome:</label>
              <input type="text" name="nome" class="form-control" id="nome" placeholder="Digite o nome completo">
            </div>
            <div class="mb-3">
              <label for="email" class="col-form-label">E-mail:</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Digite o seu e-mail">
            </div>
            <div class="mb-3">
              <label for="cpf" class="col-form-label">CPF:</label>
              <input type="text" name="cpf" class="form-control" id="cpf" placeholder="Digite o seu CPF (apenas números)">
            </div>
            <div class="mb-3">
              <label for="modalidades" class="col-form-label">Modalidades:</label>
              <select name="modalidades[]" class="form-control" id="modalidades" multiple>
                <option value="1">Futebol</option>
                <option value="2">Vôlei</option>
                <option value="3">Basquete</option>
                <!-- Adicione outras opções conforme necessário -->
              </select>
            </div>
            <div class="mb-3">
              <label for="genero" class="col-form-label">Gênero:</label>
              <select name="genero" class="form-control" id="genero">
                <option value="">Selecione</option>
                <option value="Homem">Homem</option>
                <option value="Mulher">Mulher</option>
                <option value="Outro">Outro</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary" id="cad-associado-btn">Cadastrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Visualização -->
  <div class="modal fade" id="visAssociadoModal" tabindex="-1" aria-labelledby="visAssociadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="visAssociadoModalLabel">Detalhes do Associado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <span id="msgAlertaErroVis"></span>
          <dl class="row">
            <dt class="col-sm-3">ID:</dt>
            <dd class="col-sm-9" id="visualizar_id"></dd>

            <dt class="col-sm-3">Nome:</dt>
            <dd class="col-sm-9" id="visualizar_nome"></dd>

            <dt class="col-sm-3">E-mail:</dt>
            <dd class="col-sm-9" id="visualizar_email"></dd>

            <dt class="col-sm-3">CPF:</dt>
            <dd class="col-sm-9" id="visualizar_cpf"></dd>

            <dt class="col-sm-3">Modalidade:</dt>
            <dd class="col-sm-9" id="visualizar_modalidade"></dd>

            <dt class="col-sm-3">Gênero:</dt>
            <dd class="col-sm-9" id="visualizar_genero"></dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Edição -->
  <div class="modal fade" id="editAssociadoModal" tabindex="-1" aria-labelledby="editAssociadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAssociadoModalLabel">Editar Associado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="edit-associado-form">
            <span id="msgAlertaErroEdit"></span>
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
              <label for="edit_nome" class="col-form-label">Nome:</label>
              <input type="text" name="nome" class="form-control" id="edit_nome" placeholder="Digite o nome completo">
            </div>
            <div class="mb-3">
              <label for="edit_email" class="col-form-label">E-mail:</label>
              <input type="email" name="email" class="form-control" id="edit_email" placeholder="Digite o seu e-mail">
            </div>
            <div class="mb-3">
              <label for="edit_cpf" class="col-form-label">CPF:</label>
              <input type="text" name="cpf" class="form-control" id="edit_cpf" placeholder="Digite o seu CPF (apenas números)">
            </div>
            <div class="mb-3">
              <label for="edit_modalidades" class="col-form-label">Modalidades:</label>
              <select name="modalidades[]" class="form-control" id="edit_modalidades" multiple>
                <option value="1">Futebol</option>
                <option value="2">Vôlei</option>
                <option value="3">Basquete</option>
                <!-- Adicione outras opções conforme necessário -->
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_genero" class="col-form-label">Gênero:</label>
              <select name="genero" class="form-control" id="edit_genero">
                <option value="">Selecione</option>
                <option value="Homem">Homem</option>
                <option value="Mulher">Mulher</option>
                <option value="Outro">Outro</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-warning" id="edit-associado-btn">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Exclusão -->
  <div class="modal fade" id="excluirAssociadoModal" tabindex="-1" aria-labelledby="excluirAssociadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="excluirAssociadoModalLabel">Excluir Associado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <span id="msgAlertaErroExcluir"></span>
          <p>Tem certeza que deseja excluir este associado?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="confirmar-excluir-btn">Excluir</button>
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
  
  <!-- Custom JS -->
  <script src="js/custom.js"></script>
  
  <script>
    $(document).ready(function () {
      // Inicialização do Select2 no modal de cadastro
      $('#modalidades').select2({
        theme: 'bootstrap-5',
        placeholder: "Selecione as modalidades",
        closeOnSelect: false,
        width: '100%'
      });

      // Inicialização do Select2 no modal de edição
      $('#edit_modalidades').select2({
        theme: 'bootstrap-5',
        placeholder: "Selecione as modalidades",
        closeOnSelect: false,
        width: '100%'
      });
    });

    // Função para definir o título e a mensagem de boas-vindas
    function setHeaderContent(title) {
      if (title) {
        document.getElementById('header-title').textContent = title;
      } else {
        document.getElementById('header-title').style.display = 'none';
      }
    }

    // Exemplo de uso:
    setHeaderContent("Página de Associados");
  </script>
</body>

</html>
