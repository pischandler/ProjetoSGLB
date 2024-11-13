const usuariosAtivosContainer = document.querySelector(".listar-usuarios-ativos");
const solicitacoesContainer = document.querySelector(".listar-solicitacoes");

// Listar usuários ativos
const listarUsuarios = async (pagina) => {
    const dados = await fetch("./list_ativos.php?pagina=" + pagina);
    const resposta = await dados.text();
    usuariosAtivosContainer.innerHTML = resposta;
}

function removerAlerta() {
    setTimeout(() => {
        msgAlerta.innerHTML = ""; // Limpar o conteúdo da mensagem de alerta
    }, 3000); // 3000 milissegundos = 3 segundos
}

// Listar solicitações pendentes
const listarSolicitacoes = async (pagina) => {
    const dados = await fetch("./list_pendentes.php?pagina=" + pagina);
    const resposta = await dados.text();
    solicitacoesContainer.innerHTML = resposta;
}

// Alternar entre a lista de usuários ativos e pendentes
function toggleList(listType) {
    const usuariosAtivos = document.getElementById('usuarios-ativos');
    const solicitacoes = document.getElementById('solicitacoes');

    if (listType === 'ativos') {
        usuariosAtivos.style.display = 'block';
        solicitacoes.style.display = 'none';
        listarUsuarios(1); // Carregar usuários ativos ao exibir
    } else {
        usuariosAtivos.style.display = 'none';
        solicitacoes.style.display = 'block';
        listarSolicitacoes(1); // Carregar solicitações ao exibir
    }
}

// Eventos de clique para alternar as listas
document.querySelectorAll('h4').forEach(h4 => {
    h4.addEventListener('click', () => {
        if (h4.textContent.trim() === "Usuários Ativos") {
            toggleList('ativos');
        } else if (h4.textContent.trim() === "Pendentes") {
            toggleList('pendentes');
        }
    });
});

// Carregar a lista de usuários ativos ao iniciar
listarUsuarios(1);

// Aceitar solicitação de usuário pendente
async function aceitarUsuarioPendente(id) {
    const confirmar = confirm("Tem certeza que deseja permitir o acesso do usuário ao sistema?");
  
    if (confirmar) {
        const dados = await fetch('aceitar_pendente.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + id // Enviar o ID do usuário
        });
  
        const resposta = await dados.json(); // Receber a resposta em JSON
  
        if (resposta['erro']) {
            msgAlerta.innerHTML = resposta['msg'];
            removerAlerta();
        } else {
            msgAlerta.innerHTML = resposta['msg'];
            listarUsuarios(1); // Recarregar a lista de usuários ativos
            listarSolicitacoes(1); // Recarregar a lista de solicitações pendentes
            removerAlerta();
        }
    }    
}

// Excluir usuário
async function apagarUsuarioDados(id) {
    const confirmar = confirm("Tem certeza que deseja excluir o registro selecionado?");

    if (confirmar) {
        const dados = await fetch('apagar.php?id=' + id);

        const resposta = await dados.json();
        if (resposta['erro']) {
            msgAlerta.innerHTML = resposta['msg'];
            removerAlerta();
        } else {
            msgAlerta.innerHTML = resposta['msg'];
            listarUsuarios(1); // Recarregar a lista de usuários ativos
            listarSolicitacoes(1); // Recarregar a lista de solicitações pendentes
            removerAlerta();
        }
    }    
}

document.addEventListener("DOMContentLoaded", function() {
    var helpIcon = document.getElementById('helpIcon');
    var helpModal = new bootstrap.Modal(document.getElementById('helpModal'));
    var helpContent = document.getElementById('helpContent');

    helpIcon.addEventListener('click', function() {
        var currentPage = window.location.pathname.split('/').pop();
        var helpText = getHelpContent(currentPage);
        helpContent.innerHTML = helpText;
        helpModal.show();
    });

    function getHelpContent(page) {
        switch (page) {
            case 'index.php':
                return '<p>Instruções para a página inicial...</p>';
            case 'cadastrar.php':
                return '<p>Instruções para cadastrar...</p>';
            case 'editar.php':
                return '<p>Instruções para editar...</p>';
            // Add more cases for other pages
            default:
                return `
                <p>Bem-vindo ao Painel Administrativo! Esta seção é exclusiva para administradores e permite gerenciar o acesso de usuários ao sistema.</p>
                <ul>
                    <li><strong>Aba Usuários Ativos:</strong> Exibe uma lista de usuários que têm acesso ao sistema, com as informações de ID, Nome e E-mail. Na coluna Ações, o administrador pode:
                        <ul>
                            <li><i class="fa-solid fa-x"></i> <strong>Excluir:</strong> Revogar o acesso do usuário ao sistema.</li>
                        </ul>
                    </li>
                    <li><strong>Aba Pendentes:</strong> Exibe os usuários que solicitaram acesso ao sistema, mostrando ID, Nome e E-mail. Na coluna Ações, o administrador pode:
                        <ul>
                            <li><i class="fa-solid fa-check"></i> <strong>Aceitar:</strong> Aprovar a solicitação e conceder acesso ao sistema.</li>
                            <li><i class="fa-solid fa-x"></i> <strong>Rejeitar:</strong> Recusar o pedido de acesso do usuário.</li>
                        </ul>
                    </li>
                    <li><strong>Paginação:</strong> Use a navegação na parte inferior da página para visualizar mais usuários em ambas as abas.</li>
                </ul>
                <p>Esses recursos permitem ao administrador controlar quem pode acessar o sistema de forma eficiente e segura.</p>
                `;
        }
    }
});

// Add this script before the closing </body> tag:
function switchTab(tab) {
    // Remove active class from all tabs
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    
    // Add active class to clicked tab
    document.getElementById(`tab-${tab}`).classList.add('active');
    
    // Show/hide content
    if (tab === 'ativos') {
        document.getElementById('usuarios-ativos').style.display = 'block';
        document.getElementById('solicitacoes').style.display = 'none';
    } else {
        document.getElementById('usuarios-ativos').style.display = 'none';
        document.getElementById('solicitacoes').style.display = 'block';
    }
}
