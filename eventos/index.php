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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/5414af6fb5.js" crossorigin="anonymous"></script>
    <link href="css/custom.css" rel="stylesheet">
    <link rel="icon" href="../assets/imagemLosBravos.png" type="image/png">
    <title>Eventos</title>
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

                    <h1 class="modal-title fs-5" id="visualizarModalLabel">Visualizar o Evento</h1>

                    <h1 class="modal-title fs-5" id="editarModalLabel" style="display: none;">Editar o Evento</h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <span id="msgViewEvento"></span>

                    <div id="visualizarEvento">

                        <dl class="row">
                            <dd class="col-sm-9" id="visualizar_id" style="display: none;"></dd>

                            <dt class="col-sm-3">Título: </dt>
                            <dd class="col-sm-9" id="visualizar_title"></dd>

                            <dt class="col-sm-3">Início: </dt>
                            <dd class="col-sm-9" id="visualizar_start"></dd>

                            <dt class="col-sm-3">Fim: </dt>
                            <dd class="col-sm-9" id="visualizar_end"></dd>

                            <dt class="col-sm-3">Estado: </dt>
                            <dd class="col-sm-9" id="visualizar_estado"></dd>

                            <dt class="col-sm-3">Cidade: </dt>
                            <dd class="col-sm-9" id="visualizar_cidade"></dd>

                            <dt class="col-sm-3">Local: </dt>
                            <dd class="col-sm-9" id="visualizar_local"></dd>

                            <dt class="col-sm-3">Cep: </dt>
                            <dd class="col-sm-9" id="visualizar_cep"></dd>

                            <dt class="col-sm-3">Bairro: </dt>
                            <dd class="col-sm-9" id="visualizar_bairro"></dd>

                            <dt class="col-sm-3">Rua: </dt>
                            <dd class="col-sm-9" id="visualizar_rua"></dd>

                            <dt class="col-sm-3">Número: </dt>
                            <dd class="col-sm-9" id="visualizar_numero"></dd>

                            <dt class="col-sm-3">Complemento: </dt>
                            <dd class="col-sm-9" id="visualizar_complemento"></dd>

                        </dl>

                        <button type="button" class="btn btn-warning" id="btnViewEditEvento">Editar</button>

                        <button type="button" class="btn btn-danger" id="btnApagarEvento">Apagar</button>

                    </div>

                    <div id="editarEvento" style="display: none;">

                        <span id="msgEditEvento"></span>

                        <form method="POST" id="formEditEvento">

                            <input type="hidden" name="edit_id" id="edit_id">

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
                                <label for="edit_estado" class="col-sm-2 col-form-label">Estado:</label>
                                <div class="col-sm-10">
                                    <select name="edit_estado" class="form-control" id="edit_estado">
                                        <option value="">Selecione</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_cidade" class="col-sm-2 col-form-label">Cidade:</label>
                                <div class="col-sm-10">
                                    <select name="edit_cidade" class="form-control" id="edit_cidade">
                                        <option value="">Selecione</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_local" class="col-sm-2 col-form-label">Local:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="edit_local" name="edit_local" placeholder="Digite o local">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_cep" class="col-sm-2 col-form-label">CEP:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="edit_cep" name="edit_cep" placeholder="Digite o CEP">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_bairro" class="col-sm-2 col-form-label">Bairro:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="edit_bairro" name="edit_bairro" placeholder="Digite o bairro">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_rua" class="col-sm-2 col-form-label">Rua:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="edit_rua" name="edit_rua" placeholder="Digite o nome da rua">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_numero" class="col-sm-2 col-form-label">Número:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="edit_numero" name="edit_numero" placeholder="Digite o número">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_complemento" class="col-sm-3 col-form-label">Complemento:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="edit_complemento" name="edit_complemento" placeholder="Digite o complemento">
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
                    <h1 class="modal-title fs-5" id="cadastrarModalLabel">Cadastrar o Evento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <span id="msgCadEvento"></span>

                    <form method="POST" id="formCadEvento">

                        <div class="row mb-3">
                            <label for="cad_title" class="col-sm-2 col-form-label">Título</label>
                            <div class="col-sm-10">
                                <input type="text" name="cad_title" class="form-control" id="cad_title" placeholder="Título do evento">
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
                            <label for="cad_estado" class="col-sm-2 col-form-label">Estado:</label>
                            <div class="col-sm-10">
                                <select name="cad_estado" class="form-control" id="cad_estado">
                                    <option value="">Selecione</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cad_cidade" class="col-sm-2 col-form-label">Cidade:</label>
                            <div class="col-sm-10">
                                <select name="cad_cidade" class="form-control" id="cad_cidade">
                                    <option value="">Selecione</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                                <label for="cad_local" class="col-sm-2 col-form-label">Local:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="cad_local" name="cad_local" placeholder="Digite o local">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_cep" class="col-sm-2 col-form-label">CEP:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="cad_cep" name="cad_cep" placeholder="Digite o CEP">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_bairro" class="col-sm-2 col-form-label">Bairro:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="cad_bairro" name="cad_bairro" placeholder="Digite o bairro">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_rua" class="col-sm-2 col-form-label">Rua:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="cad_rua" name="cad_rua" placeholder="Digite o nome da rua">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_numero" class="col-sm-2 col-form-label">Número:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="cad_numero" name="cad_numero" placeholder="Digite o número">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cad_complemento" class="col-sm-3 col-form-label">Complemento:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="cad_complemento" name="cad_complemento" placeholder="Digite o complemento">
                                </div>
                            </div>

                        <button type="submit" name="btnCadEvento" class="btn btn-success" id="btnCadEvento">Salvar</button>

                    </form>

                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src='js/index.global.min.js'></script>
    <script src="js/bootstrap5/index.global.min.js"></script>
    <script src='js/core/locales-all.global.min.js'></script>
    <script src='js/custom.js'></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var cep = document.getElementById("cad_cep");
            if (cep) {
                cep.addEventListener("input", () => {
                    // Remove todos os caracteres não numéricos e limita a 8 dígitos
                    var limparValor = cep.value.replace(/\D/g, '').substring(0, 8);
                    // Adiciona o hífen e os 3 dígitos finais, se existirem
                    var numeroFormatado = limparValor.replace(/(\d{5})(\d{0,3})/, '$1-$2');
                    // Atualiza o valor no campo de entrada
                    cep.value = numeroFormatado;
                });
            }

            var edit_cep = document.getElementById("edit_cep");
            if (edit_cep) {
                edit_cep.addEventListener("input", () => {
                    // Remove todos os caracteres não numéricos e limita a 8 dígitos
                    var limparValor = edit_cep.value.replace(/\D/g, '').substring(0, 8);
                    // Adiciona o hífen e os 3 dígitos finais, se existirem
                    var numeroFormatado = limparValor.replace(/(\d{5})(\d{0,3})/, '$1-$2');
                    // Atualiza o valor no campo de entrada
                    edit_cep.value = numeroFormatado;
                });
            }

            // Carregar estados
            fetch('get_estados.php')
                .then(response => response.json())
                .then(data => {
                    var selectEstado = document.getElementById('cad_estado');
                    data.estados.forEach(function(estado) {
                        var option = document.createElement('option');
                        option.value = estado.id;
                        option.textContent = estado.uf;
                        selectEstado.appendChild(option);
                    });
                })
                .catch(error => console.error('Erro ao carregar estados:', error));

            // Atualizar cidades quando um estado é selecionado
            document.getElementById('cad_estado').addEventListener('change', function() {
                var estadoId = this.value;
                var selectCidade = document.getElementById('cad_cidade');
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

            // Carregar estados
            fetch('get_estados.php')
                .then(response => response.json())
                .then(data => {
                    var selectEstado = document.getElementById('edit_estado');
                    data.estados.forEach(function(estado) {
                        var option = document.createElement('option');
                        option.value = estado.id;
                        option.textContent = estado.uf;
                        selectEstado.appendChild(option);
                    });
                })
                .catch(error => console.error('Erro ao carregar estados:', error));

            // Atualizar cidades quando um estado é selecionado
            document.getElementById('edit_estado').addEventListener('change', function() {
                var estadoId = this.value;
                var selectCidade = document.getElementById('edit_cidade');
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
        setHeaderContent("Página de Eventos");
    </script>

</body>

</html>