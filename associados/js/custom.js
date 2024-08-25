// Seleção dos elementos do DOM
const tbody = document.querySelector(".listar-associados");
const cadForm = document.getElementById("cad-associado-form");
const editForm = document.getElementById("edit-associado-form");
const msgAlerta = document.getElementById("msgAlerta"); 
const msgAlertaErroCad = document.getElementById("msgAlertaErroCad");
const msgAlertaErroEdit = document.getElementById("msgAlertaErroEdit");
const cadModal = new bootstrap.Modal(document.getElementById("cadAssociadoModal"));
const searchInput = document.getElementById("searchTerm");



// Função para formatar CPF
function formatarCPF(cpf) {
    cpf = cpf.replace(/\D/g, ''); // Remove caracteres não numéricos

    if (cpf.length <= 3) {
        return cpf;
    } else if (cpf.length <= 6) {
        return cpf.substring(0, 3) + '.' + cpf.substring(3);
    } else if (cpf.length <= 9) {
        return cpf.substring(0, 3) + '.' + cpf.substring(3, 6) + '.' + cpf.substring(6);
    } else {
        return cpf.substring(0, 3) + '.' + cpf.substring(3, 6) + '.' + cpf.substring(6, 9) + '-' + cpf.substring(9, 11);
    }
}

// Adiciona formatação ao CPF nos formulários
document.getElementById('cpf').addEventListener('input', function(e) {
    e.target.value = formatarCPF(e.target.value);
});

document.getElementById('edit_cpf').addEventListener('input', function(e) {
    e.target.value = formatarCPF(e.target.value);
});

// Função para listar associados
const listarAssociados = async (pagina, searchTerm = "") => {
    const dados = await fetch(`./list.php?pagina=${pagina}&searchTerm=${encodeURIComponent(searchTerm)}`);
    const resposta = await dados.text();
    tbody.innerHTML = resposta;
}

// Função para atualizar a lista com base na pesquisa
const atualizarListaComPesquisa = () => {
    const searchTerm = searchInput.value;
    listarAssociados(1, searchTerm);
}

// Adiciona evento para pesquisa
searchInput.addEventListener("input", atualizarListaComPesquisa);

// Chama listarAssociados com a página 1 e pesquisa vazia na inicialização
listarAssociados(1);

// Evento de submissão do formulário de cadastro
cadForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    
    document.getElementById("cad-associado-btn").value = "Salvando...";

    // Remove a formatação do CPF antes de enviar
    const cpf = document.getElementById("cpf").value.replace(/\D/g, '');

    if (document.getElementById("nome").value === "") {
        msgAlertaErroCad.innerHTML = "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>";
    } else if (document.getElementById("email").value === "") {
        msgAlertaErroCad.innerHTML = "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!</div>";
    } else {
        const dadosForm = new FormData(cadForm);
        dadosForm.set('cpf', cpf); // Atualiza o campo cpf sem formatação
        dadosForm.append("add", 1);

        const dados = await fetch("cadastrar.php", {
            method: "POST",
            body: dadosForm,
        });

        const resposta = await dados.json();
        if (resposta['erro']) {
            msgAlertaErroCad.innerHTML = resposta['msg'];
        } else {
            msgAlerta.innerHTML = resposta['msg'];
            cadForm.reset();
            cadModal.hide();
            atualizarListaComPesquisa();
        }
    }

    document.getElementById("cad-associado-btn").value = "Cadastrar";
});

// Função para visualizar os detalhes do associado
async function visAssociado(id) {
    const dados = await fetch('visualizar.php?id=' + id);

    const resposta = await dados.json();

    if (resposta['erro']) {
        msgAlerta.innerHTML = resposta['msg'];
    } else {
        const visModal = new bootstrap.Modal(document.getElementById("visAssociadoModal"));
        visModal.show();

        document.getElementById("visualizar_id").innerHTML = resposta['dados'].id;
        document.getElementById("visualizar_nome").innerHTML = resposta['dados'].nome;
        document.getElementById("visualizar_email").innerHTML = resposta['dados'].email;
        document.getElementById("visualizar_cpf").innerHTML = formatarCPF(resposta['dados'].cpf); // Aplica a formatação
        document.getElementById("visualizar_genero").innerHTML = resposta['dados'].genero;
        document.getElementById("visualizar_modalidade").innerHTML = resposta['dados'].modalidades; // Adiciona as modalidades
        
    }
}

// Função para carregar os dados do associado no formulário de edição
async function editAssociadoDados(id) {
    msgAlertaErroEdit.innerHTML = "";

    const dados = await fetch('visualizar.php?id=' + id);
    const resposta = await dados.json();

    if (resposta['erro']) {
        msgAlerta.innerHTML = resposta['msg'];
    } else {
        const editModal = new bootstrap.Modal(document.getElementById("editAssociadoModal"));
        editModal.show();
        document.getElementById("edit_id").value = resposta['dados'].id;
        document.getElementById("edit_nome").value = resposta['dados'].nome;
        document.getElementById("edit_email").value = resposta['dados'].email;
        document.getElementById("edit_cpf").value = formatarCPF(resposta['dados'].cpf); // Aplica a formatação
        document.getElementById("edit_genero").value = resposta['dados'].genero;

        // Divida as modalidades em um array
        const modalidades = resposta['dados'].modalidades.split(',').map(id => id.trim());

        console.log(modalidades); // Verifica o array de IDs de modalidades

        // Defina os valores selecionados no Select2
        $('#edit_modalidades').val(modalidades).trigger('change');
    }
}

// Evento de submissão do formulário de edição
editForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    document.getElementById("edit-associado-btn").value = "Salvando...";

    // Remove a formatação do CPF antes de enviar
    const cpf = document.getElementById("edit_cpf").value.replace(/\D/g, '');

    const dadosForm = new FormData(editForm);
    dadosForm.set('cpf', cpf); // Atualiza o campo cpf sem formatação

    const dados = await fetch("editar.php", {
        method: "POST",
        body: dadosForm
    });

    const resposta = await dados.json();

    if (resposta['erro']) {
        msgAlertaErroEdit.innerHTML = resposta['msg'];
    } else {
        msgAlertaErroEdit.innerHTML = resposta['msg'];
        atualizarListaComPesquisa();
    }

    document.getElementById("edit-associado-btn").value = "Salvar";
});

// Função para confirmar a exclusão de um associado
async function apagarAssociadoDados(id) {
    const excluirModal = new bootstrap.Modal(document.getElementById("excluirAssociadoModal"));
    excluirModal.show();

    // Definir o ID do associado no botão de confirmação de exclusão
    document.getElementById("confirmar-excluir-btn").setAttribute('data-id', id);
}

// Evento para excluir o associado ao confirmar no modal
document.getElementById("confirmar-excluir-btn").addEventListener("click", async () => {
    const id = document.getElementById("confirmar-excluir-btn").getAttribute('data-id');
    const dados = await fetch(`apagar.php?id=${id}`);
    const resposta = await dados.json();
    
    if (resposta['erro']) {
        msgAlerta.innerHTML = resposta['msg'];
    } else {
        msgAlerta.innerHTML = resposta['msg'];
        atualizarListaComPesquisa();
        const excluirModal = bootstrap.Modal.getInstance(document.getElementById("excluirAssociadoModal"));
        excluirModal.hide();
    }
    
});


