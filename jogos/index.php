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

                            <div class="row mb-3"></div>
                            <dt class="d-flex align-items-center justify-content-center">Início: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_start"></dd>

                            <dt class="d-flex align-items-center justify-content-center">Fim: </dt>
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_end"></dd>

                            <!--<dt class="col-sm-3">Modalidade: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_modalidade"></dd>

                            <!--<dt class="col-sm-3">Gênero: </dt>-->
                            <dd class="d-flex align-items-center justify-content-center" id="visualizar_gen"></dd>

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
                                        <option value="Capetada">Capetada</option>
                                        <option value="XV">XV</option>
                                        <option value="Sharks">Sharks</option>
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
                                        <option value="Capetada" data-logo="../assets/logo_capetada.png">Capetada</option>
                                        <option value="XV">XV</option>
                                        <option value="Sharks" data-logo="../assets/logo_sharks.blob">Sharks</option>
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
                            <label for="cad_gen" class="col-sm-2 col-form-label">Gênero</label>
                            <div class="col-sm-10">
                                <select name="cad_gen" class="form-control" id="cad_gen">
                                    <option value="">Selecione</option>
                                    <option value="Homem">Homem</option>
                                    <option value="Mulher">Mulher</option>
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

        document.addEventListener('DOMContentLoaded', function() {
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

            fetch('get_associados.php')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('cad_associados');
                    select.innerHTML = ''; // Limpar as opções existentes
                    data.forEach(associado => {
                        const option = document.createElement('option');
                        option.value = associado.id;
                        option.textContent = associado.nome;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Erro ao buscar associados:', error));
            fetch('get_associados.php')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('edit_associados');
                    select.innerHTML = ''; // Limpar as opções existentes
                    data.forEach(associado => {
                        const option = document.createElement('option');
                        option.value = associado.id;
                        option.textContent = associado.nome;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Erro ao buscar associados:', error));
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
    </script>

</body>

</html>