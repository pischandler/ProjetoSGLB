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
