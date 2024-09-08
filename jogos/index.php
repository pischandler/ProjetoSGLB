<?php
session_start();
if (empty($_SESSION['id'])) {
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário fazer login!</div>";
    header("Location: ../login/");
    exit();
}

// Função para gerar o header com saudação personalizada
// botao sair       <a class='btn btn-secondary btn-sm' href='../login/' role='button'>Sair <i class='fa-solid fa-arrow-right-from-bracket'></i></a>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/5414af6fb5.js" crossorigin="anonymous"></script>
    <link href="css/custom.css" rel="stylesheet">
    <title>Página Inicial</title>
    <style>
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
    <div class="row mb-4">

    </div>

    <div class="container">


        <span id="msg"></span>

        <div id='calendar'></div>

    </div>

    <!-- Modal Visualizar -->
    <div class="modal fade" id="visualizarModal" tabindex="-1" aria-labelledby="visualizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h1 class="modal-title fs-5" id="visualizarModalLabel">Visualizar o Jogo</h1>

                    <h1 class="modal-title fs-5" id="editarModalLabel" style="display: none;">Editar Jogo</h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <span id="msgViewEvento"></span>

                    <div id="visualizarEvento">

                        <dl class="row">

                            <dt class="col-sm-3" style="display: none;">ID: </dt>
                            <dd class="col-sm-9" id="visualizar_id" style="display: none;"></dd>

                            <!--<dt class="col-sm-3">Título: </dt>-->
                            <h5 class="d-flex align-items-center justify-content-center" style="font-size: 20px; font-weight: bold;" id="visualizar_title"></h5>
                            <!--<span class="d-flex align-items-center justify-content-center" id="visualizar_modalidade"></span>-->

                            <!--<dt class="col-sm-3">Adversário: </dt>-->
                            <div class="d-flex align-items-center justify-content-center">
                                <!-- Logo da equipe -->
                                <div class="team">
                                    <img src="../assets/imagemLosBravos.png" alt="Los Bravos" class="teamlogo" style="width: 130px; height: 130px;">
                                </div>

                                <!-- X no meio -->
                                <div class="versus d-flex align-items-center mx-3" style="font-size: 48px; font-weight: bold;">
                                    <span>X</span>
                                </div>

                                <!-- Logo do adversário -->
                                <div class="adversario d-flex align-items-center">
                                    <img id="visualizar_logo_adversario" src="" alt="Logo do adversário" style="height: 130px;">
                                    <span id="visualizar_adversario"></span>
                                </div>
                            </div>
                            <div id="modalPlacar" class="d-flex align-items-center justify-content-center">
                                <div id="inputPlacar" style="display: none;">
                                    <label for="placar_casa">Placar Casa:</label>
                                    <input type="number" id="placar_casa">
                                    <br>
                                    <label for="placar_adversario">Placar Adversário:</label>
                                    <input type="number" id="placar_adversario">
                                    <br>
                                    <button class="btn btn-success" id="btnSalvarPlacar">Salvar Placar</button>
                                    <button type="button" class="btn btn-primary" id="btnCancelar">Cancelar</button>
                                </div>
                                <button class="btn btn-primary" id="btnPlacar">Adicionar Placar</button>
                            </div>

                            <div class="row mb-3"></div>
                            <dt class="d-flex align-items-center justify-content-center">Início: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_start"></dd>

                            <dt class="d-flex align-items-center justify-content-center">Fim: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_end"></dd>

                            <!--<dt class="col-sm-3">Modalidade: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_modalidade"></dd>

                            <!--<dt class="col-sm-3">Gênero: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_genero"></dd>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_placar"></dd>

                            <!--<dt class="col-sm-3">Associados: </dt>
                            <dd class="col-sm-9" id="visualizar_associados"></dd>-->

                        </dl>

                        <button type="button" class="btn btn-warning" id="btnViewEditEvento">Editar</button>

                        <button type="button" class="btn btn-danger" id="btnApagarEvento">Apagar</button>

                        <!-- Botão para mostrar detalhes -->
                        <button type="button" class="btn btn-info" id="btnShowDetails">Mostrar convocação</button>

                        <!-- Área de detalhes oculta -->
                        <div id="eventDetails" style="display: none;">
                            <div class="row mb-2"></div>
                            <h5>
                                <dt>Atletas Convocados</dt>
                            </h5>
                            <p id="eventDetailsContent"></p>
                        </div>
                    </div>

                    <div id="editarEvento" style="display: none;">

                        <span id="msgEditEvento"></span>

                        <form method="POST" id="formEditEvento">

                            <input type="hidden" name="edit_id" id="edit_id">

                            <div class="row mb-3">
                                <label for="edit_title" class="col-sm-2 col-form-label">Título</label>
                                <div class="col-sm-10">
                                    <input type="text" name="edit_title" class="form-control" id="edit_title" placeholder="Título do Jogo">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_adversario" class="col-sm-2 col-form-label">Adversário</label>
                                <div class="col-sm-10">
                                    <select name="edit_adversario" class="form-control" id="edit_adversario">
                                        <option value="">Selecione</option>
                                        <option value="A.A.i.J">A.A.i.J</option>
                                        <option value="A.A.A.Z">A.A.A.Z</option>
                                        <option value="Barbaros">Bárbaros</option>
                                        <option value="Caoticos">Caóticos</option>
                                        <option value="Capetada">Capetada</option>
                                        <option value="Coringaco">Coringaço</option>
                                        <option value="Corvos">Corvos</option>
                                        <option value="Direito">Direito UEPG</option>
                                        <option value="Gorilas">Gorilas</option>
                                        <option value="Hornets">Hornets</option>
                                        <option value="Hunters">Hunters</option>
                                        <option value="Javas">Javas</option>
                                        <option value="Medicina">Medicina UEPG</option>
                                        <option value="Sharks">Sharks</option>
                                        <option value="Soberana">Soberana</option>
                                        <option value="Troia">Tróia</option>
                                        <option value="VI">VI de Novembro</option>
                                        <option value="XIX">XIX de Setembro</option>
                                        <option value="XV">XV de Outubro</option>

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="edit_start" class="col-sm-2 col-form-label">Início</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_start" class="form-control" id="edit_start">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_end" class="col-sm-2 col-form-label">Fim</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_end" class="form-control" id="edit_end">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_color" class="col-sm-2 col-form-label">Cor</label>
                                <div class="col-sm-10">
                                    <select name="edit_color" class="form-control" id="edit_color">
                                        <option value="">Selecione</option>
                                        <option style="color:#FFD700;" value="#FFD700">Amarelo</option>
                                        <option style="color:#0071c5;" value="#0071c5">Azul Turquesa</option>
                                        <option style="color:#FF4500;" value="#FF4500">Laranja</option>
                                        <option style="color:#8B4513;" value="#8B4513">Marrom</option>
                                        <option style="color:#1C1C1C;" value="#1C1C1C">Preto</option>
                                        <option style="color:#436EEE;" value="#436EEE">Royal Blue</option>
                                        <option style="color:#A020F0;" value="#A020F0">Roxo</option>
                                        <option style="color:#40E0D0;" value="#40E0D0">Turquesa</option>
                                        <option style="color:#228B22;" value="#228B22">Verde</option>
                                        <option style="color:#8B0000;" value="#8B0000">Vermelho</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_genero" class="col-sm-2 col-form-label">Gênero</label>
                                <div class="col-sm-10">
                                    <select name="edit_genero" class="form-control" id="edit_genero">
                                        <option value="">Selecione</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Adicionar Modalidade -->
                            <div class="row mb-3">
                                <label for="edit_modalidade" class="col-sm-2 col-form-label">Modalidade</label>
                                <div class="col-sm-10">
                                    <select name="edit_modalidade" class="form-control" id="edit_modalidade">
                                        <option value="">Selecione</option>
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
                                        <!-- Adicione mais modalidades conforme necessário -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="filtrar_por_modalidade">
                                <label class="form-check-label" for="filtrar_por_modalidade">
                                    Filtrar por modalidade
                                </label>
                            </div>

                            <!-- Adicionar Seleção de Associados -->
                            <div class="row mb-3">
                                <label for="edit_associados" class="col-sm-2 col-form-label">Associados</label>
                                <div class="col-sm-10">
                                    <select name="associados[]" class="form-control" id="edit_associados" multiple>
                                        <!-- As opções serão preenchidas dinamicamente -->
                                    </select>
                                </div>
                            </div>

                            <button type="button" name="btnViewEvento" class="btn btn-primary" id="btnViewEvento">Cancelar</button>

                            <button type="submit" name="btnEditEvento" class="btn btn-warning" id="btnEditEvento">Salvar</button>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar -->
    <div class="modal fade" id="cadastrarModal" tabindex="-1" aria-labelledby="cadastrarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cadastrarModalLabel">Cadastrar Jogo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <span id="msgCadEvento"></span>

                    <form method="POST" id="formCadEvento">


                        <div class="row mb-3">
                            <label for="cad_title" class="col-sm-2 col-form-label">Título</label>
                            <div class="col-sm-10">
                                <select name="cad_title" class="form-control" id="cad_title">
                                    <option value="">Selecione</option>
                                    <option value="Amistoso">Amistoso</option>
                                    <option value="Jogo-treino">Jogo-treino</option>
                                    <option value="Jogo Oficial">Jogo Oficial</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cad_adversario" class="col-sm-2 col-form-label">Adversário</label>
                            <div class="col-sm-10 d-flex align-items-center justify-content-center" style="gap: 20px;">
                                <!-- Imagem da equipe -->
                                <div class="team">
                                    <img src="../assets/imagemLosBravos.png" alt="Los Bravos" class="team-logo" style="width: 80px; height: 80px;">
                                </div>
                                <div class="versus d-flex align-items-center" style="font-size: 48px; font-weight: bold;">
                                    <span>X</span>
                                </div>
                                <div class="adversario d-flex align-items-center">
                                    <img src="../assets/logo_default.png" id="adversario_logo" alt="Logo do adversário" class="team-logo" style="width: 80px; height: 80px;">
                                    <!-- Select adversário -->
                                    <select name="cad_adversario" class="form-control" id="cad_adversario" style="margin-left: 10px;">
                                        <option value="">Selecione</option>
                                        <option value="A.A.i.J" data-logo="../assets/logo_AAIJ.png">A.A.i.J</option>
                                        <option value="A.A.A.Z" data-logo="../assets/logo_AAAZ.png">A.A.A.Z</option>
                                        <option value="Barbaros" data-logo="">Bárbaros</option>
                                        <option value="Caoticos" data-logo="../assets/logo_caoticos.png">Caóticos</option>
                                        <option value="Capetada" data-logo="../assets/logo_capetada.png">Capetada</option>
                                        <option value="Coringaco" data-logo="../assets/logo_coringaco.png">Coringaço</option>
                                        <option value="Corvos" data-logo="">Corvos</option>
                                        <option value="Direito" data-logo="../assets/logo_direito.png">Direito UEPG</option>
                                        <option value="Gorilas" data-logo="../assets/logo_gorilas.png">Gorilas</option>
                                        <option value="Hornets" data-logo="../assets/logo_hornets.png">Hornets</option>
                                        <option value="Hunters" data-logo="../assets/logo_hunters.png">Hunters</option>
                                        <option value="Javas" data-logo="../assets/logo_javas.png">Javas</option>
                                        <option value="Medicina" data-logo="">Medicina UEPG</option>
                                        <option value="Sharks" data-logo="../assets/logo_sharks.blob">Sharks</option>
                                        <option value="Soberana" data-logo="../assets/logo_soberana.png">Soberana</option>
                                        <option value="Troia" data-logo="../assets/logo_troia.png">Tróia</option>
                                        <option value="VI" data-logo="">VI de Novembro</option>
                                        <option value="XIX" data-logo="../assets/logo_XIX.png">XIX de Setembro</option>
                                        <option value="XV" data-logo="../assets/logo_XV.png">XV de Outubro</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="cad_start" class="col-sm-2 col-form-label">Início</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" name="cad_start" class="form-control" id="cad_start">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cad_end" class="col-sm-2 col-form-label">Fim</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" name="cad_end" class="form-control" id="cad_end">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cad_color" class="col-sm-2 col-form-label">Cor</label>
                            <div class="col-sm-10">
                                <select name="cad_color" class="form-control" id="cad_color">
                                    <option value="">Selecione</option>
                                    <option style="color:#FFD700;" value="#FFD700">Amarelo</option>
                                    <option style="color:#0071c5;" value="#0071c5">Azul Turquesa</option>
                                    <option style="color:#FF4500;" value="#FF4500">Laranja</option>
                                    <option style="color:#8B4513;" value="#8B4513">Marrom</option>
                                    <option style="color:#1C1C1C;" value="#1C1C1C">Preto</option>
                                    <option style="color:#436EEE;" value="#436EEE">Royal Blue</option>
                                    <option style="color:#A020F0;" value="#A020F0">Roxo</option>
                                    <option style="color:#40E0D0;" value="#40E0D0">Turquesa</option>
                                    <option style="color:#228B22;" value="#228B22">Verde</option>
                                    <option style="color:#8B0000;" value="#8B0000">Vermelho</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="cad_genero" class="col-sm-2 col-form-label">Gênero</label>
                            <div class="col-sm-10">
                                <select name="cad_genero" class="form-control" id="cad_genero">
                                    <option value="">Selecione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Feminino">Feminino</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                        </div>
                        <!-- Adicionar Modalidade -->
                        <div class="row mb-3">
                            <label for="cad_modalidade" class="col-sm-2 col-form-label">Modalidade</label>
                            <div class="col-sm-10">
                                <select name="cad_modalidade" class="form-control" id="cad_modalidade">
                                    <option value="">Selecione</option>
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
                                    <!-- Adicione mais modalidades conforme necessário -->
                                </select>
                            </div>
                        </div>


                        <!-- Div Oculta para Seleção de Associados -->
                        <div id="associadosDiv" style="display: none;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cad_filtrar_por_modalidade">
                                <label class="form-check-label" for="cad_filtrar_por_modalidade">
                                    Filtrar por modalidade
                                </label>
                            </div>
                            <div class="row mb-3">
                                <label for="cad_associados" class="col-sm-2 col-form-label">Associados</label>
                                <div class="col-sm-10">
                                    <select name="associados[]" class="form-control" id="cad_associados" multiple>
                                        <!-- As opções serão preenchidas dinamicamente -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botão para Exibir Seleção de Associados -->
                        <div class="row mb-3">
                            <div class="col-sm-12 text-center">
                                <button type="button" id="convocarAssociadosBtn" class="btn btn-link">Convocar Aletas</button>
                            </div>
                        </div>

                        <button type="submit" name="btnCadEvento" class="btn btn-success" id="btnCadEvento">Cadastrar</button>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src='js/index.global.min.js'></script>
    <script src="js/bootstrap5/index.global.min.js"></script>
    <script src='js/core/locales-all.global.min.js'></script>
    <script src='js/custom.js'></script>

    <script>
        // Função para resetar o modal

        // Função para exibir os inputs de placar com valores existentes, se disponíveis

        // Função para salvar o placar





        // Supondo que você tenha uma forma de chamar openModal com os dados do evento
        // Exemplo:
        // openModal({ placar_casa: 4, placar_adversario: 3 });
        document.getElementById('cad_adversario').addEventListener('change', function() {
            // Obtém a imagem correspondente ao adversário selecionado
            var logoSrc = this.options[this.selectedIndex].getAttribute('data-logo');

            // Atualiza o atributo src da imagem para o brasão do adversário selecionado
            document.getElementById('adversario_logo').src = logoSrc ? logoSrc : '../assets/logo_default.png';
        });

        // JavaScript para alternar a visibilidade da div de associados e o texto do botão
        document.getElementById('convocarAssociadosBtn').addEventListener('click', function() {
            var associadosDiv = document.getElementById('associadosDiv');
            var button = document.getElementById('convocarAssociadosBtn');

            if (associadosDiv.style.display === 'none') {
                associadosDiv.style.display = 'block';
                button.textContent = 'Mostrar menos';
            } else {
                associadosDiv.style.display = 'none';
                button.textContent = 'Convocar Associados';
            }
        });

        // Ocultar a seleção de associados e redefinir o texto do botão quando o modal for fechado
        var cadastrarModal = document.getElementById('cadastrarModal');
        cadastrarModal.addEventListener('hidden.bs.modal', function() {
            var associadosDiv = document.getElementById('associadosDiv');
            var button = document.getElementById('convocarAssociadosBtn');

            // Ocultar a div e redefinir o texto do botão
            associadosDiv.style.display = 'none';
            button.textContent = 'Convocar Associados';
        });

        document.getElementById('cad_adversario').addEventListener('change', function() {
            // Pega a opção selecionada
            var selectedOption = this.options[this.selectedIndex];

            // Pega o valor do data-logo da opção selecionada
            var logoUrl = selectedOption.getAttribute('data-logo');

            // Atualiza o src da imagem com o id "adversario_logo"
            document.getElementById('adversario_logo').src = logoUrl ? logoUrl : '../assets/logo_default.png';
        });

        document.addEventListener('DOMContentLoaded', function() {

            $(document).ready(function() {
                // Inicialização do Select2 no modal de cadastro
                $('#cad_associados').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Selecione os associados",
                    closeOnSelect: false,
                    width: '100%'
                });

                $('#edit_associados').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Selecione os associados",
                    closeOnSelect: false,
                    width: '100%'
                });

                // Inicialização do Select2 no modal de edição
            });

            const adversarioLogo = document.getElementById('adversario_logo');
            const defaultLogo = '../assets/logo_default.png';

            // Reseta a imagem do adversário para a padrão ao fechar o modal de cadastro de jogo
            const cadastrarModal = document.getElementById('cadastrarModal');
            cadastrarModal.addEventListener('hidden.bs.modal', function() {
                adversarioLogo.src = defaultLogo;
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

            // Função para atualizar a lista de associados no cadastro
            // Função para atualizar a lista de associados no cadastro
            function atualizarAssociados() {
                const genero = document.getElementById('cad_genero').value;
                const modalidade = document.getElementById('cad_modalidade').value;
                const filtrarPorModalidade = document.getElementById('cad_filtrar_por_modalidade').checked; // ID corrigido para "cad_filtrar_por_modalidade"
                const select = document.getElementById('cad_associados');

                console.log('Gênero:', genero);
                console.log('Modalidade:', modalidade);
                console.log('Filtrar por Modalidade:', filtrarPorModalidade);

                // Capturar os IDs e nomes dos associados que já estão selecionados
                const selecionados = Array.from(select.options)
                    .filter(option => option.selected)
                    .map(option => ({
                        id: option.value,
                        nome: option.textContent
                    }));

                console.log('Selecionados:', selecionados);

                // Construir a URL da requisição dependendo se o filtro de modalidade está ativado
                let url = `get_associados.php?genero=${encodeURIComponent(genero)}`;
                if (filtrarPorModalidade) {
                    url += `&modalidade=${encodeURIComponent(modalidade)}`;
                }

                console.log('URL:', url);

                // Enviar requisição ao PHP com os parâmetros de gênero e modalidade (se aplicável)
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Dados recebidos:', data);

                        select.innerHTML = ''; // Limpar as opções existentes

                        // Manter as opções previamente selecionadas
                        selecionados.forEach(associado => {
                            const option = document.createElement('option');
                            option.value = associado.id;
                            option.textContent = associado.nome;
                            option.selected = true;
                            select.appendChild(option);
                        });

                        // Adicionar as novas opções de associados filtrados
                        data.forEach(associado => {
                            if (!selecionados.some(sel => sel.id === associado.id)) {
                                const option = document.createElement('option');
                                option.value = associado.id;
                                option.textContent = associado.nome;
                                select.appendChild(option);
                            }
                        });

                        // Reinicializar o Select2 para refletir as mudanças
                        $('#cad_associados').select2({
                            theme: 'bootstrap-5',
                            placeholder: "Selecione os associados",
                            closeOnSelect: false,
                            width: '100%'
                        });
                    })
                    .catch(error => console.error('Erro ao buscar associados:', error));
            }

            // Adicionar eventos para chamar atualizarAssociados quando o gênero, modalidade ou checkbox mudar
            document.getElementById('cad_genero').addEventListener('change', atualizarAssociados);
            document.getElementById('cad_modalidade').addEventListener('change', atualizarAssociados);
            document.getElementById('cad_filtrar_por_modalidade').addEventListener('change', atualizarAssociados); // ID corrigido para "cad_filtrar_por_modalidade"


            // Função para atualizar a lista de associados no modal de edição
            function atualizarAssociadosEdicao() {
                const genero = document.getElementById('edit_genero').value;
                const modalidade = document.getElementById('edit_modalidade').value;
                const filtrarPorModalidade = document.getElementById('filtrar_por_modalidade').checked; // Verificar se o checkbox está marcado
                const select = document.getElementById('edit_associados');

                // Capturar os IDs e nomes dos associados que já estão selecionados
                const selecionados = Array.from(select.options)
                    .filter(option => option.selected)
                    .map(option => ({
                        id: option.value,
                        nome: option.textContent
                    }));

                // Construir a URL da requisição dependendo se o filtro de modalidade está ativado
                let url = `get_associados.php?genero=${encodeURIComponent(genero)}`;
                if (filtrarPorModalidade) {
                    url += `&modalidade=${encodeURIComponent(modalidade)}`;
                }

                // Enviar requisição ao PHP com os parâmetros de gênero e modalidade (se aplicável)
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        select.innerHTML = ''; // Limpar as opções existentes

                        // Manter as opções previamente selecionadas
                        selecionados.forEach(associado => {
                            const option = document.createElement('option');
                            option.value = associado.id;
                            option.textContent = associado.nome; // Manter o nome original
                            option.selected = true;
                            select.appendChild(option);
                        });

                        // Adicionar as novas opções de associados filtrados
                        data.forEach(associado => {
                            if (!selecionados.some(sel => sel.id === associado.id)) {
                                const option = document.createElement('option');
                                option.value = associado.id;
                                option.textContent = associado.nome;
                                select.appendChild(option);
                            }
                        });

                        // Reinicializar o Select2 para refletir as mudanças
                        $('#edit_associados').select2({
                            theme: 'bootstrap-5',
                            placeholder: "Selecione os associados",
                            closeOnSelect: false,
                            width: '100%'
                        });
                    })
                    .catch(error => console.error('Erro ao buscar associados:', error));
            }

            // Adicionar eventos para chamar atualizarAssociados quando o gênero, modalidade ou checkbox mudar

            document.getElementById('edit_genero').addEventListener('change', atualizarAssociadosEdicao);
            document.getElementById('edit_modalidade').addEventListener('change', atualizarAssociadosEdicao);
            document.getElementById('filtrar_por_modalidade').addEventListener('change', atualizarAssociadosEdicao);

            // Forçar a atualização dos associados ao abrir o modal de edição
            document.getElementById('editModal').addEventListener('show.bs.modal', function() {
                atualizarAssociadosEdicao();
            });


            // Função para definir o título e a mensagem de boas-vindas
            function setHeaderContent(title, welcomeMessage) {
                if (title) {
                    document.getElementById('header-title').textContent = title;
                } else {
                    document.getElementById('header-title').style.display = 'none';
                }

                if (welcomeMessage) {
                    document.getElementById('welcome-message').textContent = welcomeMessage + ", ...";
                } else {
                    document.getElementById('welcome-message').style.display = 'none';
                }
            }

            // Exemplo de uso:
            setHeaderContent("Calendário de Jogos");
        });
    </script>

</body>

</html>