<?php
session_start();
if (empty($_SESSION['id'])) {
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário fazer login!</div>";
    header("Location: ../login/");
    exit();
}
// botao sair       <a class='btn btn-secondary btn-sm' href='../login/' role='button'>Sair <i class='fa-solid fa-arrow-right-from-bracket'></i></a>
// Função para gerar o header com saudação personalizada
function gerarHeader($nomeUsuario)
{
    return "
    <header class='header'>
        <div class='header-left'>
            <button class='btn btn-primary' type='button' data-bs-toggle='offcanvas' data-bs-target='#offcanvasWithBothOptions' aria-controls='offcanvasWithBothOptions'>
                <i class='fa-solid fa-bars'></i>
            </button>
            <a href='/SGLB/home'>
                <img src='https://losbravosuepg.com.br/assets/logolb.png' height='100' alt='Los Bravos' title='Página Inicial.'>
            </a>
        </div>
        <h1 id='header-title'>Bem-vindo, " . $_SESSION['nome'] . "</h1>
    </header>";
}

$header = gerarHeader($_SESSION['nome']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/5414af6fb5.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../assets/imagemLosBravos.png" type="image/png">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f0f0f0;
            /* Fundo cinza claro */
            color: #fff;
            /* Texto preto suave */
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

        .btn-primary {
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

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <div class="dashboard-section grafico">
                    <h2>Gráfico de Jogos</h2>
                    <canvas id="victoryDonutChart"></canvas>
                </div>
            </div>
            <div class="col-md-9">
                <div class="dashboard-section">
                    <h2>Histórico de Jogos</h2>
                    <div id="historico-cards-container"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h2>Próximos Jogos</h2>
                    <div id="jogos-cards-container"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h2>Próximos Eventos</h2>
                    <div id="event-cards-container"></div>
                </div>
            </div>
        </div>


        <!-- Modal Visualizar -->
        <div class="modal fade" id="visualizarModal" tabindex="-1" aria-labelledby="visualizarModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="modalDialog">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="visualizarModalLabel">Visualizar o Evento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody">
                        <dl class="row">
                            <span class="col-sm-9" id="visualizar_id" style="display: none;"></span>

                            <dt class="col-sm-3">Título: </dt>
                            <dd class="col-sm-9" id="visualizar_title"></dd>

                            <dt class="col-sm-3">Início: </dt>
                            <dd class="col-sm-9" id="visualizar_start"></dd>

                            <dt class="col-sm-3">Fim: </dt>
                            <dd class="col-sm-9" id="visualizar_end"></dd>
                        </dl>
                        <div class="d-flex mt-3">
                            <button type="button" class="btn btn-warning me-2" id="btnViewEditEvento">Editar</button>
                            <button type="button" class="btn btn-danger" id="btnApagarEvento">Apagar</button>
                        </div>
                    </div>
                    <div id="editarEvento" style="display: none;">
                        <span id="msgEditEvento"></span>
                        <form method="POST" id="formEditEvento">
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="row mb-3"></div>
                            <div class="row mb-3">
                                <label for="edit_title" class="col-sm-2 col-form-label">Título</label>
                                <div class="col-sm-10">
                                    <input type="text" name="edit_title" class="form-control" id="edit_title" placeholder="Título do evento">
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

                            <button type="button" name="btnViewEvento" class="btn btn-primary" id="btnViewEvento">Cancelar</button>
                            <button type="submit" name="btnEditEvento" class="btn btn-warning" id="btnEditEvento">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Visualizar -->
        <div class="modal fade" id="visualizarModal_jogos" style="color: #000;" tabindex="-1" aria-labelledby="visualizarModalLabel_jogos" aria-hidden="true">
            <div class="modal-dialog" id="modalDialog_jogos">
                <div class="modal-content" id="modalContent_jogos">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="visualizarModalLabel_jogos">Visualizar o Jogo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody_jogos">
                        <dl class="row">

                            <dt class="col-sm-3" style="display: none;">ID: </dt>
                            <dd class="col-sm-9" id="visualizar_id_jogos" style="display: none;"></dd>

                            <!--<dt class="col-sm-3">Título: </dt>-->
                            <h5 class="d-flex align-items-center justify-content-center" style="font-size: 20px; font-weight: bold;" id="visualizar_title_jogos"></h5>
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
                                    <img id="visualizar_logo_adversario_jogos" src="" alt="Logo do adversário" style="padding-left: 8px;height: 130px;">
                                    <span id="visualizar_adversario_jogos"></span>
                                </div>
                            </div>


                            <div class="row mb-3"></div>
                            <dt class="d-flex align-items-center justify-content-center">Início: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_start_jogos"></dd>

                            <dt class="d-flex align-items-center justify-content-center">Fim: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_end_jogos"></dd>

                            <!--<dt class="col-sm-3">Modalidade: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_modalidade_jogos"></dd>

                            <!--<dt class="col-sm-3">Gênero: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_gen_jogos"></dd>

                            <!--<dt class="col-sm-3">Associados: </dt>
                            <dd class="col-sm-9" id="visualizar_associados"></dd>-->

                        </dl>
                        <div class="d-flex mt-3">
                            <button type="button" class="btn btn-warning me-2" id="btnViewEditEvento_jogos">Editar</button>
                            <button type="button" class="btn btn-danger me-2" id="btnApagarEvento_jogos">Apagar</button>
                            <button type="button" class="btn btn-info" id="btnShowDetails">Mostrar convocação</button>
                        </div>
                        <!-- Área de detalhes oculta -->
                        <div id="eventDetails1" style="display: none;">
                            <div class="row mb-2"></div>
                            <h5>
                                <dt>Atletas Convocados</dt>
                            </h5>
                            <p id="eventDetailsContent"></p>
                        </div>
                    </div>


                    <div id="editarEvento_jogos" style="display: none;">
                        <span id="msgEditEvento_jogos"></span>
                        <div class="row mb-3"></div>


                        <form method="POST" id="formEditEvento_jogos">

                            <input type="hidden" name="edit_id_jogos" id="edit_id_jogos">

                            <div class="row mb-3">
                                <label for="edit_title_jogos" class="col-sm-2 col-form-label">Título</label>
                                <div class="col-sm-10">
                                    <select name="edit_title_jogos" class="form-control" id="edit_title_jogos">
                                        <option value="">Selecione</option>
                                        <option value="Amistoso">Amistoso</option>
                                        <option value="Jogo-treino">Jogo-treino</option>
                                        <option value="Jogo Oficial">Jogo Oficial</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_adversario_jogos" class="col-sm-2 col-form-label">Adversário</label>
                                <div class="col-sm-10">
                                    <select name="edit_adversario_jogos" class="form-control" id="edit_adversario_jogos">
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
                                <label for="edit_start_jogos" class="col-sm-2 col-form-label">Início</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_start_jogos" class="form-control" id="edit_start_jogos">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_end_jogos" class="col-sm-2 col-form-label">Fim</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_end_jogos" class="form-control" id="edit_end_jogos">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_color_jogos" class="col-sm-2 col-form-label">Cor</label>
                                <div class="col-sm-10">
                                    <select name="edit_color_jogos" class="form-control" id="edit_color_jogos">
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
                                <label for="edit_genero_jogos" class="col-sm-2 col-form-label">Gênero</label>
                                <div class="col-sm-10">
                                    <select name="edit_genero_jogos" class="form-control" id="edit_genero_jogos">
                                        <option value="">Selecione</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Adicionar Modalidade -->
                            <div class="row mb-3">
                                <label for="edit_modalidade_jogos" class="col-sm-3 col-form-label">Modalidade</label>
                                <div class="col-sm-9">
                                    <select name="edit_modalidade_jogos" class="form-control" id="edit_modalidade_jogos">
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



                            <!-- Adicionar Seleção de Associados -->
                            <div class="row mb-1">
                                <label for="edit_associados_jogos" class="col-sm-3 col-form-label">Associados</label>
                                <div class="col-sm-9">
                                    <select name="associados[]" class="form-control" id="edit_associados_jogos" multiple>
                                        <!-- As opções serão preenchidas dinamicamente -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="form-check" style="padding-left: 160px;">
                                    <input class="form-check-input" type="checkbox" id="filtrar_por_modalidade">
                                    <label class="form-check-label" for="filtrar_por_modalidade">
                                        Filtrar por modalidade
                                    </label>
                                </div>
                            </div>

                            <button type="button" name="btnViewEvento_jogos" class="btn btn-primary" id="btnViewEvento_jogos">Cancelar</button>

                            <button type="submit" name="btnEditEvento_jogos" class="btn btn-warning" id="btnEditEvento_jogos">Salvar</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Visualizar -->
        <div class="modal fade" id="visualizarModal_historico" style="color: #000;" tabindex="-1" aria-labelledby="visualizarModalLabel_historico" aria-hidden="true">
            <div class="modal-dialog" id="modalDialog_historico">
                <div class="modal-content" id="modalContent_historico">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="visualizarModalLabel_historico">Visualizar o Jogo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody_historico">
                        <span id="msgPlacarEvento_historico"></span>

                        <dl class="row">

                            <dt class="col-sm-3" style="display: none;">ID: </dt>
                            <dd class="col-sm-9" id="visualizar_id_historico" style="display: none;"></dd>

                            <!--<dt class="col-sm-3">Título: </dt>-->
                            <h5 class="d-flex align-items-center justify-content-center" style="font-size: 20px; font-weight: bold;" id="visualizar_title_historico"></h5>
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
                                    <img id="visualizar_logo_adversario_historico" src="" alt="Logo do adversário" style="padding-left: 8px;height: 130px;">
                                    <span id="visualizar_adversario_historico"></span>
                                </div>
                            </div>
                            <div class="row mb-3"></div>
                            <div id="modalPlacar" class="d-flex flex-column align-items-center justify-content-center">
                                <div id="inputPlacar" class="p-3 border rounded" style="display: none; width: 100%; max-width: 400px;">
                                    <div class="form-group mb-3">
                                        <label for="placar_casa" class="form-label">Placar Casa:</label>
                                        <input type="number" class="form-control" id="placar_casa" min="0" placeholder="Digite o placar da casa">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="placar_adversario" class="form-label">Placar Adversário:</label>
                                        <input type="number" class="form-control" id="placar_adversario" min="0" placeholder="Digite o placar do adversário">
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-success" id="btnSalvarPlacar">Salvar Placar</button>
                                        <button type="button" class="btn btn-secondary" id="btnCancelar">Cancelar</button>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-3" id="btnPlacar">Adicionar Placar</button>
                            </div>

                            <div class="row mb-3"></div>
                            <dt class="d-flex align-items-center justify-content-center">Início: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_start_historico"></dd>

                            <dt class="d-flex align-items-center justify-content-center">Fim: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_end_historico"></dd>

                            <!--<dt class="col-sm-3">Modalidade: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_modalidade_historico"></dd>

                            <!--<dt class="col-sm-3">Gênero: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_gen_historico"></dd>

                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_placar"></dd>

                            <!--<dt class="col-sm-3">Associados: </dt>
<dd class="col-sm-9" id="visualizar_associados"></dd>-->

                        </dl>
                        <div class="d-flex mt-3">
                            <button type="button" class="btn btn-warning me-2" id="btnViewEditEvento_historico">Editar</button>
                            <button type="button" class="btn btn-danger me-2" id="btnApagarEvento_historico">Apagar</button>
                            <button type="button" class="btn btn-info" id="btnShowDetails2">Mostrar convocação</button>
                        </div>
                        <!-- Área de detalhes oculta -->
                        <div id="eventDetails2" style="display: none;">
                            <div class="row mb-2"></div>
                            <h5>
                                <dt>Atletas Convocados</dt>
                            </h5>
                            <p id="eventDetailsContent2"></p>
                        </div>
                    </div>

                    <div id="editarEvento_historico" style="display: none;">
                        <span id="msgEditEvento_historico"></span>
                        <div class="row mb-3"></div>


                        <form method="POST" id="formEditEvento_historico">

                            <input type="hidden" name="edit_id_historico" id="edit_id_historico">

                            <div class="row mb-3">
                                <label for="edit_title_historico" class="col-sm-2 col-form-label">Título</label>
                                <div class="col-sm-10">
                                    <select name="edit_title_historico" class="form-control" id="edit_title_historico">
                                        <option value="">Selecione</option>
                                        <option value="Amistoso">Amistoso</option>
                                        <option value="Jogo-treino">Jogo-treino</option>
                                        <option value="Jogo Oficial">Jogo Oficial</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_adversario_historico" class="col-sm-2 col-form-label">Adversário</label>
                                <div class="col-sm-10">
                                    <select name="edit_adversario_historico" class="form-control" id="edit_adversario_historico">
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
                                <label for="edit_start_historico" class="col-sm-2 col-form-label">Início</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_start_historico" class="form-control" id="edit_start_historico">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_end_historico" class="col-sm-2 col-form-label">Fim</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_end_historico" class="form-control" id="edit_end_historico">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_color_historico" class="col-sm-2 col-form-label">Cor</label>
                                <div class="col-sm-10">
                                    <select name="edit_color_historico" class="form-control" id="edit_color_historico">
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
                                <label for="edit_genero_historico" class="col-sm-2 col-form-label">Gênero</label>
                                <div class="col-sm-10">
                                    <select name="edit_genero_historico" class="form-control" id="edit_genero_historico">
                                        <option value="">Selecione</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Adicionar Modalidade -->
                            <div class="row mb-3">
                                <label for="edit_modalidade_historico" class="col-sm-3 col-form-label">Modalidade</label>
                                <div class="col-sm-9">
                                    <select name="edit_modalidade_historico" class="form-control" id="edit_modalidade_historico">
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

                            <!-- Adicionar Seleção de Associados -->
                            <div class="row mb-1">
                                <label for="edit_associados_historico" class="col-sm-3 col-form-label">Associados</label>
                                <div class="col-sm-9">
                                    <select name="associados[]" class="form-control" id="edit_associados_historico" multiple>
                                        <!-- As opções serão preenchidas dinamicamente -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="form-check" style="padding-left: 160px;">
                                    <input class="form-check-input" type="checkbox" id="filtrar_por_modalidade_historico">
                                    <label class="form-check-label" for="filtrar_por_modalidade_historico">
                                        Filtrar por modalidade
                                    </label>
                                </div>
                            </div>

                            <button type="button" name="btnViewEvento_historico" class="btn btn-primary" id="btnViewEvento_historico">Cancelar</button>

                            <button type="submit" name="btnEditEvento_historico" class="btn btn-warning" id="btnEditEvento_historico">Salvar</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>



        <!--<div class="col-md-3">
                <div class="dashboard-section" id="aniversariantes">
                    <h2>Aniversariantes</br>do Dia</h2>
                    <ul>
                        <li>João - 21/08/1990</li>
                        <li>Maria - 21/08/1985</li>
                        <li>Carlos - 21/08/2000</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button id="show-game" class="btn btn-info">Mostrar Jogo <i class="fa-solid fa-chevron-down"></i></button>
        </div>
        <div class="text-center">
            <button id="start-button" class="btn btn-success">Start</button>
        </div>
        <div class="game" style="display: none;"> 
            <span class="score"></span>
            <div class="dino"></div>
            <div class="cacto"></div>
        </div>

        <script src="script.js"></script>
    </div>
    <script src="script.js"></script>-->

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./js/home_eventos/custom_eventos.js"></script>
        <script src="./js/home_jogos/custom_jogos.js"></script>
        <script src="./js/home_historico/custom_historico.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="./js/donutChart.js"></script>


        <script>
            $(document).ready(function() {
                $('#edit_associados_jogos').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Selecione os associados",
                    closeOnSelect: false,
                    width: '100%'
                });

                // Inicialização do Select2 no modal de edição
            });


            $(document).ready(function() {
                $('#edit_associados_historico').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Selecione os associados",
                    closeOnSelect: false,
                    width: '100%'
                });

                // Inicialização do Select2 no modal de edição
            });

            document.addEventListener("DOMContentLoaded", function() {
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
            });
        </script>

</body>

</html>