<?php
// Função para gerar o sidebar
function gerarSidebar($paginaAtual) {
    $isHome = $paginaAtual === 'home';
    $isAssociados = $paginaAtual === 'associados';
    $isEventos = $paginaAtual === 'eventos';
    $isJogos = $paginaAtual === 'jogos';
    $isAdm = $paginaAtual === 'administrativo';
    $isSair = $paginaAtual === 'sair';

    // Verifica se o usuário é administrador
    $isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'administrador';

    return "
    <div class='offcanvas offcanvas-start sidebar' data-bs-scroll='true' tabindex='-1' id='offcanvasWithBothOptions' aria-labelledby='offcanvasWithBothOptionsLabel'>
        <div class='offcanvas-header'>
            <h5 class='offcanvas-title' id='offcanvasWithBothOptionsLabel'>Menu</h5>
            <button type='button' class='btn-close' data-bs-dismiss='offcanvas' aria-label='Close'></button>
        </div>
        <div class='offcanvas-body'>
            <div class='list-group'>
                <a href='/SGLB/home' class='list-group-item list-group-item-action " . ($isHome ? "highlight-home" : "") . "'><i class='fa-solid fa-house'></i> Home</a>
                <a href='/SGLB/associados' class='list-group-item list-group-item-action " . ($isAssociados ? "highlight-home" : "") . "'><i class='fa-solid fa-users'></i> Associados</a>
                <a href='/SGLB/eventos' class='list-group-item list-group-item-action " . ($isEventos ? "highlight-home" : "") . "'><i class='fa-solid fa-calendar'></i> Eventos</a>
                <a href='/SGLB/jogos' class='list-group-item list-group-item-action " . ($isJogos ? "highlight-home" : "") . "'><i class='fa-regular fa-futbol'></i></i> Jogos</a>"
                // Exibe o link "Administrador" apenas se o usuário for um administrador
               . ($isAdmin ? "
                <a href='/SGLB/administrativo' class='list-group-item list-group-item-action " . ($isAdm ? "highlight-home" : "") . "'><i class='fa-solid fa-user-tie'></i> Administrador</a>" : "") . "
                <a href='../sair.php' class='list-group-item list-group-item-action " . ($isSair ? "highlight-home" : "") . "'><i class='fa-solid fa-arrow-right-from-bracket'></i> Sair</a>
            </div>
        </div>
    </div>";
}

// Determine a página atual com base na URL
$paginaAtual = basename($_SERVER['REQUEST_URI'], ".php");

?>

<!-- Inserir o sidebar gerado pela função -->
<?php echo gerarSidebar($paginaAtual); ?>
