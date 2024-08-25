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
            <button class='btn btn-primary' type='button' data-bs-toggle='offcanvas' data-bs-target='#offcanvasWithBothOptions' aria-controls='offcanvasWithBothOptions'>
                <i class='fa-solid fa-bars'></i>
            </button>
            <a href='/SGLB/home'>
                <img src='https://losbravosuepg.com.br/assets/logolb.png' height='100' alt='Los Bravos' title='Página Inicial.'>
            </a>
        </div>
        <h1 id='header-title'>Bem-vindo, " . $_SESSION['nome'] . "</h1>
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5414af6fb5.js" crossorigin="anonymous"></script>
    <title>Página Inicial</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            color: #ffffff;
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

        .btn-primary {
            background-color: #ee3a34;
            border-color: #ee3a34;
        }

        .highlight-home {
            color: #1b98e0 !important;
        }

        .dashboard-section {
            background-color: #2c2c2c;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .dashboard-section h2 {
            color: #ffffff;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .dashboard-section ul {
            list-style-type: none;
            padding-left: 0;
        }

        .dashboard-section ul li {
            color: #c2c2c2;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Inserir o header gerado pela função -->
    <?php echo $header; ?>

    <!-- Inserir o sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h2>Próximos Jogos</h2>
                    <ul>
                        <li>Jogo 1 - 25/08/2024</li>
                        <li>Jogo 2 - 30/08/2024</li>
                        <li>Jogo 3 - 05/09/2024</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h2>Próximos Eventos</h2>
                    <ul>
                        <li>Evento 1 - 22/08/2024</li>
                        <li>Evento 2 - 28/08/2024</li>
                        <li>Evento 3 - 02/09/2024</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h2>Aniversariantes do Dia</h2>
                    <ul>
                        <li>João - 21/08/1990</li>
                        <li>Maria - 21/08/1985</li>
                        <li>Carlos - 21/08/2000</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-section">
                    <h2>Histórico de Jogos</h2>
                    <ul>
                        <li>Jogo 1 - Vitória</li>
                        <li>Jogo 2 - Derrota</li>
                        <li>Jogo 3 - Empate</li>
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
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>