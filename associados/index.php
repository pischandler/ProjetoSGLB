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
            <a href='/home'>
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

  <!-- Custom CSS -->
  <link href="./css/custom.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Select2 Bootstrap Theme -->
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/5414af6fb5.js" crossorigin="anonymous"></script>
  <link rel="icon" href="../assets/imagemLosBravos.png" type="image/png">


  <title>Associados</title>

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
            <span style="font-size: small;">Campos Obrigatórios (<span class="required">*</span> )</span>
            <div class="mb-3">
              <label for="nome" class="col-form-label">Nome:<span class="required">*</span></label>
              <input type="text" name="nome" class="form-control" id="nome" placeholder="Digite o nome completo">
            </div>

            <div class="mb-3">
              <label for="curso" class="col-form-label">Curso:<span class="required">*</span></label>
              <select name="curso" class="form-control" id="curso">
                <option value="">Selecione</option>
                <option value="Engeharia Agronômica">Engeharia Agronômica</option>
                <option value="Engenharia Civil">Engenharia Civil</option>
                <option value="Engenharia de Alimentos">Engenharia de Alimentos</option>
                <option value="Engenharia de Computação">Engenharia de Computação</option>
                <option value="Engenharia de Materiais">Engenharia de Materiais</option>
                <option value="Engenharia de Software">Engenharia de Software</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="email" class="col-form-label">E-mail:<span class="required">*</span></label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Digite o seu e-mail">
            </div>
            <div class="mb-3">
              <label for="cpf" class="col-form-label">CPF:<span class="required">*</span></label>
              <input type="text" name="cpf" class="form-control" id="cpf" placeholder="Digite o seu CPF (apenas números)">
            </div>

            <div class="mb-3">
              <label for="ra" class="col-form-label">RA:<span class="required">*</span></label>
              <input type="text" name="ra" class="form-control" id="ra" placeholder="Digite o seu RA (apenas números)">
            </div>
            <div class="mb-3">
              <label for="celular" class="col-form-label">Celular:<span class="required">*</span></label>
              <input type="text" name="celular" class="form-control" id="celular" placeholder="(XX)XXXX-XXXX">
            </div>
            <div class="mb-3">
              <label for="modalidades" class="col-form-label">Modalidades:<span class="required">*</span></label>
              <select name="modalidades[]" class="form-control" id="modalidades" multiple>
                <option value="1">Atletismo</option>
                <option value="2">Basquetebol</option>
                <option value="3">Futebol</option>
                <option value="4">Futsal</option>
                <option value="5">Handebol</option>
                <option value="6">Judô</option>
                <option value="7">Natação</option>
                <option value="8">Tênis</option>
                <option value="9">Tênis de Mesa</option>
                <option value="10">Voleibol</option>
                <option value="11">Vôlei de Praia</option>
                <option value="12">Xadrez</option>
                <!-- Adicione outras opções conforme necessário -->
              </select>
            </div>
            <div class="mb-3">
              <label for="genero" class="col-form-label">Gênero:<span class="required">*</span></label>
              <select name="genero" class="form-control" id="genero">
                <option value="">Selecione</option>
                <option value="Feminino">Feminino</option>
                <option value="Masculino">Masculino</option>
              </select>
            </div>

            <div class="row">
              <div class="col-sm-3 mb-3">
                <label for="estado" class="col-form-label">UF:<span class="required">*</span></label>
                <select name="estado" class="form-control" id="estado">
                  <option value="">Selecione</option>
                  <!-- Adicione as opções aqui -->
                </select>
              </div>

              <div class="col-sm-9 mb-3">
                <label for="cidade" class="col-form-label">Cidade:<span class="required">*</span></label>
                <select name="cidade" class="form-control" id="cidade">
                  <option value="">Selecione</option>
                  <!-- Adicione as opções aqui -->
                </select>
              </div>
            </div>

            <!-- Botão para alternar a visibilidade -->
            <div class="text-center mb-3">
              <button class="btn btn-link" type="button" id="toggle-button" onclick="toggleVisibility()">Cadastrar Informações de Local de Residência</button>
            </div>

            <!-- Div que será alternada -->
            <div id="residence-info" style="display: none;">
              <div class="mb-3">
                <label for="cep" class="col-form-label">Cep:</label>
                <input type="cep" name="cep" class="form-control" id="cep" placeholder="Digite o seu CEP">
              </div>

              <div class="mb-3">
                <label for="bairro" class="col-form-label">Bairro:</label>
                <input type="bairro" name="bairro" class="form-control" id="bairro" placeholder="Digite o seu bairro">
              </div>

              <div class="mb-3">
                <label for="rua" class="col-form-label">Rua:</label>
                <input type="rua" name="rua" class="form-control" id="rua" placeholder="Digite a sua rua">
              </div>
              <div class="mb-3">
                <label for="numero_resid" class="col-form-label">Número da Residência:</label>
                <input type="numero_resid" name="numero_resid" class="form-control" id="numero_resid" placeholder="Digite o número da residência">
              </div>
              <div class="mb-3">
                <label for="complemento" class="col-form-label">Complemento:</label>
                <input type="complemento" name="complemento" class="form-control" id="complemento" placeholder="Digite o complemento">
              </div>
            </div>
            <!-- Botão para alternar a visibilidade -->

            <div class="text-center mb-3">
              <button class="btn btn-link" type="button" id="toggle-button2" onclick="toggleVisibility2()">Cadastrar Informações do Responsável</button>
            </div>

            <!-- Div que será alternada -->
            <div id="responsavel-info" style="display: none;">
              <div class="mb-3">
                <label for="nome_responsavel" class="col-form-label">Nome do Responsável:</label>
                <input type="nome_responsavel" name="nome_responsavel" class="form-control" id="nome_responsavel" placeholder="Digite nome do responsável">
              </div>
              <div class="mb-3">
                <label for="telefone_responsavel" class="col-form-label">Telefone do Responsável:</label>
                <input type="telefone_responsavel" name="telefone_responsavel" class="form-control" id="telefone_responsavel" placeholder="Digite telefone do responsável">
              </div>
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
            <!--<dt class="col-sm-3">ID:</dt>-->
            <dd class="col-sm-9" id="visualizar_id" style="display: none;"></dd>

            <dt class="col-sm-3">Nome:</dt>
            <dd class="col-sm-9" id="visualizar_nome"></dd>

            <dt class="col-sm-3">RA:</dt>
            <dd class="col-sm-9" id="visualizar_ra"></dd>

            <dt class="col-sm-3">Curso:</dt>
            <dd class="col-sm-9" id="visualizar_curso"></dd>

            <dt class="col-sm-3">E-mail:</dt>
            <dd class="col-sm-9" id="visualizar_email"></dd>

            <dt class="col-sm-3">CPF:</dt>
            <dd class="col-sm-9" id="visualizar_cpf"></dd>

            <dt class="col-sm-3">Modalidade:</dt>
            <dd class="col-sm-9" id="visualizar_modalidade"></dd>

            <dt class="col-sm-3">Gênero:</dt>
            <dd class="col-sm-9" id="visualizar_genero"></dd>

            <dt class="col-sm-3">Celular:</dt>
            <dd class="col-sm-9" id="visualizar_celular"></dd>

            <dd class="col-sm-9">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input big-checkbox" id="visualizar_ativo" checked disabled>
                  <label class="form-check-label big-checkbox-label" for="visualizar_ativo">Ativo</label>
                </div>

                <dd class="col-sm-9">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input big-checkbox" id="visualizar_ativo" disabled>
                  <label class="form-check-label big-checkbox-label" for="visualizar_ativo">Formado</label>
                </div>

            <!-- Botão para alternar detalhes do local -->
            <button type="button" class="btn btn-link" id="btnDetalhesLocal">Mostrar Informações do Local</button>

            <!-- Div com os detalhes do local -->
            <div class="detalhes_local" id="detalhes_local" style="display:none;">
              <dl class="row">
                <dt class="col-sm-3">UF:</dt>
                <dd class="col-sm-9" id="visualizar_estado"></dd>
                <dt class="col-sm-3">Cidade:</dt>
                <dd class="col-sm-9" id="visualizar_cidade"></dd>
                <dt class="col-sm-3">CEP:</dt>
                <dd class="col-sm-9" id="visualizar_cep"></dd>
                <dt class="col-sm-3">Bairro:</dt>
                <dd class="col-sm-9" id="visualizar_bairro"></dd>
                <dt class="col-sm-3">Rua:</dt>
                <dd class="col-sm-9" id="visualizar_rua"></dd>
                <dt class="col-sm-3">Número:</dt>
                <dd class="col-sm-9" id="visualizar_numero_resid"></dd>
                <dt class="col-sm-3">Complemento:</dt>
                <dd class="col-sm-9" id="visualizar_complemento"></dd>
              </dl>
            </div>



            <!-- Botão para alternar a exibição da div "detalhes_responsavel" -->
            <button type="button" class="btn btn-link" id="btnDetalhesResponsavel">Mostrar Informações do Responsável</button>

            <!-- Div "detalhes_responsavel" -->
            <div class="detalhes_responsavel" id="detalhes_responsavel" style="display:none;">
              <dl class="row">
                <dt class="col-sm-6">Nome do Responsável:</dt>
                <dd class="col-sm-4" id="visualizar_nome_responsavel"></dd>

                <dt class="col-sm-6">Telefone do Responsável:</dt>
                <dd class="col-sm-4" id="visualizar_telefone_responsavel"></dd>
              </dl>
            </div>
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
          <span style="font-size: small;">Campos Obrigatórios (<span class="required">*</span> )</span>
          <form id="edit-associado-form">
            <span id="msgAlertaErroEdit"></span>
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
              <label for="edit_nome" class="col-form-label">Nome:<span class="required">*</span></label>
              <input type="text" name="nome" class="form-control" id="edit_nome" placeholder="Digite o nome completo">
            </div>


            
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input big-checkbox" id="edit_formado" name="formado">
              <label class="form-check-label big-checkbox-label" for="edit_formado">Formado</label>
            </div>

            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input big-checkbox" id="edit_ativo" name="ativo" checked>
              <label class="form-check-label big-checkbox-label" for="edit_ativo">Ativo</label>
            </div>

            <div class="mb-3">
              <label for="edit_curso" class="col-form-label">Curso:<span class="required">*</span></label>
              <select name="curso" class="form-control" id="edit_curso">
                <option value="">Selecione</option>
                <option value="Engeharia Agronômica">Engeharia Agronômica</option>
                <option value="Engenharia Civil">Engenharia Civil</option>
                <option value="Engenharia de Alimentos">Engenharia de Alimentos</option>
                <option value="Engenharia de Computação">Engenharia de Computação</option>
                <option value="Engenharia de Materiais">Engenharia de Materiais</option>
                <option value="Engenharia de Software">Engenharia de Software</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="col-form-label">E-mail:<span class="required">*</span></label>
              <input type="email" name="email" class="form-control" id="edit_email" placeholder="Digite o seu e-mail">
            </div>
            <div class="mb-3">
              <label for="edit_cpf" class="col-form-label">CPF:<span class="required">*</span></label>
              <input type="text" name="cpf" class="form-control" id="edit_cpf" placeholder="Digite o seu CPF (apenas números)">
            </div>
            <div class="mb-3">
              <label for="edit_ra" class="col-form-label">RA:<span class="required">*</span></label>
              <input type="text" name="ra" class="form-control" id="edit_ra" placeholder="Digite o seu RA (apenas números)">
            </div>
            <div class="mb-3">
              <label for="edit_celular" class="col-form-label">Celular:<span class="required">*</span></label>
              <input type="text" name="celular" class="form-control" id="edit_celular" placeholder="(XX)XXXX-XXXX">
            </div>

            <div class="mb-3">
              <label for="edit_modalidades" class="col-form-label">Modalidades:<span class="required">*</span></label>
              <select name="modalidades[]" class="form-control" id="edit_modalidades" multiple>
                <option value="1">Atletismo</option>
                <option value="2">Basquetebol</option>
                <option value="3">Futebol</option>
                <option value="4">Futsal</option>
                <option value="5">Handebol</option>
                <option value="6">Judô</option>
                <option value="7">Natação</option>
                <option value="8">Tênis</option>
                <option value="9">Tênis de Mesa</option>
                <option value="10">Voleibol</option>
                <option value="11">Vôlei de Praia</option>
                <option value="12">Xadrez</option>
                <!-- Adicione outras opções conforme necessário -->
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_genero" class="col-form-label">Gênero:<span class="required">*</span></label>
              <select name="genero" class="form-control" id="edit_genero">
                <option value="">Selecione</option>
                <option value="Feminino">Feminino</option>
                <option value="Masculino">Masculino</option>
              </select>
            </div>

            <div class="row">
              <div class="col-sm-3 mb-3">
                <label for="edit_estado" class="col-form-label">UF:<span class="required">*</span></label>
                <select name="estado" class="form-control" id="edit_estado">
                  <option value="">Selecione</option>
                  <!-- Adicione as opções aqui -->
                </select>
              </div>

              <div class="col-sm-9 mb-3">
                <label for="edit_cidade" class="col-form-label">Cidade:<span class="required">*</span></label>
                <select name="cidade" class="form-control" id="edit_cidade">
                  <option value="">Selecione</option>
                  <!-- Adicione as opções aqui -->
                </select>
              </div>
            </div>

            <!-- Botão para alternar a visibilidade -->
            <div class="text-center mb-3">
              <button class="btn btn-link" type="button" id="toggle-button3" onclick="toggleVisibility()">Editar Informações de Local de Residência</button>
            </div>

            <!-- Div que será alternada -->
            <div id="residence-info3" style="display: none;">
              <dl class="row">
                <div class="mb-3">
                  <label for="edit_cep" class="col-form-label">Cep:</label>
                  <input type="text" name="cep" class="form-control" id="edit_cep" placeholder="Digite o seu cep">
                </div>

                <div class="mb-3">
                  <label for="edit_bairro" class="col-form-label">Bairro:</label>
                  <input type="text" name="bairro" class="form-control" id="edit_bairro" placeholder="Digite o seu bairro">
                </div>

                <div class="mb-3">
                  <label for="edit_rua" class="col-form-label">Rua:</label>
                  <input type="text" name="rua" class="form-control" id="edit_rua" placeholder="Digite a sua rua">
                </div>

                <div class="mb-3">
                  <label for="edit_numero_resid" class="col-form-label">Número da Residência:</label>
                  <input type="text" name="numero_resid" class="form-control" id="edit_numero_resid" placeholder="Digite o número da residência">
                </div>

                <div class="mb-3">
                  <label for="edit_complemento" class="col-form-label">Complemento:</label>
                  <input type="text" name="complemento" class="form-control" id="edit_complemento" placeholder="Digite o complemento">
                </div>
              </dl>
            </div>

            <script>
              function toggleVisibility() {
                var element = document.getElementById('residence-info');
                var button = document.getElementById('toggle-button');
                if (element.style.display === "none") {
                  element.style.display = "block";
                  button.textContent = "Ocultar Informações de Local de Residência";
                  element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                  });


                } else {
                  element.style.display = "none";
                  button.textContent = "Cadastrar Informações de Local de Residência";
                }

                var element = document.getElementById('residence-info3');
                var button = document.getElementById('toggle-button3');
                if (element.style.display === "none") {
                  element.style.display = "block";
                  button.textContent = "Ocultar Informações de Local de Residência";
                  element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                  });


                } else {
                  element.style.display = "none";
                  button.textContent = "Cadastrar Informações de Local de Residência";
                }
              }
            </script>

            <!-- Botão para alternar a visibilidade -->
            <div class="text-center mb-3">
              <button class="btn btn-link" type="button" id="toggle-button3" onclick="toggleVisibility2()">Editar Informações do Responsável</button>
            </div>

            <!-- Div que será alternada -->
            <div id="responsavel-info3" style="display: none;">
              <dl class="row">
                <div class="mb-3">
                  <label for="edit_nome_responsavel" class="col-form-label">Nome do Responsável:</label>
                  <input type="edit_nome_responsavel" name="nome_responsavel" class="form-control" id="edit_nome_responsavel" placeholder="Digite o seu e-mail">
                </div>
                <div class="mb-3">
                  <label for="edit_telefone_responsavel" class="col-form-label">Telefone do Responsável:</label>
                  <input type="edit_telefone_responsavel" name="telefone_responsavel" class="form-control" id="edit_telefone_responsavel" placeholder="Digite o seu e-mail">
                </div>
              </dl>
            </div>

            <script>
              function toggleVisibility2() {
                var element = document.getElementById('responsavel-info');
                var button = document.getElementById('toggle-button2');
                if (element.style.display === "none") {
                  element.style.display = "block";
                  button.textContent = "Ocultar Informações do Responsável";
                  element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                  });
                } else {
                  element.style.display = "none";
                  button.textContent = "Cadastrar Informações do Responsável";
                }


                var element = document.getElementById('responsavel-info3');
                var button = document.getElementById('toggle-button3');
                if (element.style.display === "none") {
                  element.style.display = "block";
                  button.textContent = "Ocultar Informações do Responsável";
                  element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                  });
                } else {
                  element.style.display = "none";
                  button.textContent = "Cadastrar Informações do Responsável";
                }
              }
            </script>


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
    document.addEventListener("DOMContentLoaded", function() {
      // Carregar estados
      fetch('get_estados.php')
        .then(response => response.json())  // Tenta converter a resposta para JSON
        .then(data => {
            var selectEstado = document.getElementById('estado');
            if (data.estados) {
                data.estados.forEach(function(estado) {
                    var option = document.createElement('option');
                    option.value = estado.id;
                    option.textContent = estado.uf;
                    selectEstado.appendChild(option);
                });
            } else {
                console.error('Erro ao carregar estados:', data.error || 'Erro desconhecido');
            }
        })
        .catch(error => console.error('Erro ao carregar estados:', error));

      // Atualizar cidades quando um estado é selecionado
      document.getElementById('estado').addEventListener('change', function() {
        var estadoId = this.value;
        var selectCidade = document.getElementById('cidade');
        selectCidade.innerHTML = '<option value="">Selecione a Cidade</option>'; // Limpar opções existentes

        if (estadoId) {
          fetch('get_cidades.php?estado_id=' + estadoId)
            .then(response => response.json())
            .then(data => {
              data.cidades.forEach(function(cidade) {
                var option = document.createElement('option');
                option.value = cidade.id;
                option.textContent = cidade.nome;
                selectCidade.appendChild(option);
              });
            })
            .catch(error => console.error('Erro ao carregar cidades:', error));
        }
      });

      var lastScrollTop = 0;
      var header = document.querySelector(".header");

      window.addEventListener("scroll", function() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
          // Scroll para baixo, esconde o header
          header.style.top = "-110px"; // Altura do header
        } else {
          // Scroll para cima, mostra o header
          header.style.top = "0";
        }

        lastScrollTop = scrollTop;
      });

      var editBtn = document.getElementById('edit-associado-btn');
      var modal = document.getElementById('editAssociadoModal');

      editBtn.addEventListener('click', function() {
        modal.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    });

    $(document).ready(function() {
      function initializeSelect2() {
        $('#modalidades').select2({
          theme: 'bootstrap-5',
          placeholder: "Selecione as modalidades",
          closeOnSelect: false,
          width: '100%'
        });

        $('#edit_modalidades').select2({
          theme: 'bootstrap-5',
          placeholder: "Selecione as modalidades",
          closeOnSelect: false,
          width: '100%'
        });
      }

      // Inicializa o Select2 ao carregar a página
      initializeSelect2();

      // Atualiza o Select2 quando o modal de cadastro é mostrado
      $('#cadAssociadoModal').on('shown.bs.modal', function() {
        $('#modalidades').select2('destroy').select2({
          theme: 'bootstrap-5',
          placeholder: "Selecione as modalidades",
          closeOnSelect: false,
          width: '100%'
        });
      });

      // Atualiza o Select2 quando o modal de edição é mostrado
      $('#editAssociadoModal').on('shown.bs.modal', function() {
        $('#edit_modalidades').select2('destroy').select2({
          theme: 'bootstrap-5',
          placeholder: "Selecione as modalidades",
          closeOnSelect: false,
          width: '100%'
        });
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