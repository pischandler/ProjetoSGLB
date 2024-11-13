// Seleção dos elementos do DOM
const tbody = document.querySelector(".listar-associados");
const cadForm = document.getElementById("cad-associado-form");
const editForm = document.getElementById("edit-associado-form");
const msgAlerta = document.getElementById("msgAlerta");
const msgAlertaErroCad = document.getElementById("msgAlertaErroCad");
const msgAlertaErroEdit = document.getElementById("msgAlertaErroEdit");
const cadModal = new bootstrap.Modal(document.getElementById("cadAssociadoModal"));
const searchInput = document.getElementById("searchTerm");


var ra = document.getElementById("ra");
ra.addEventListener("input", () => {

    // Remove todos os caracteres não numéricos e limita a 8 dígitos
    var limparValor = ra.value.replace(/\D/g, '').substring(0, 8);

    // Converte o valor limpo em um array de números
    var numerosArray = limparValor.split("");

    var numeroFormatado = "";

    // Adiciona os dígitos ao campo RA sem qualquer formatação
    if (numerosArray.length > 0) {
        numeroFormatado += numerosArray.join("");
    }

    // Atualiza o valor no campo de entrada
    ra.value = numeroFormatado;

});

var edit_ra = document.getElementById("edit_ra");
edit_ra.addEventListener("input", () => {

    // Remove todos os caedit_racteres não numéricos e limita a 8 dígitos
    var limparValor = edit_ra.value.replace(/\D/g, '').substring(0, 8);

    // Converte o valor limpo em um aredit_ray de números
    var numerosArray = limparValor.split("");

    var numeroFormatado = "";

    // Adiciona os dígitos ao campo RA sem qualquer formatação
    if (numerosArray.length > 0) {
        numeroFormatado += numerosArray.join("");
    }

    // Atualiza o valor no campo de entrada
    edit_ra.value = numeroFormatado;

});



var cep = document.getElementById("cep");
cep.addEventListener("input", () => {

    // Remove todos os caracteres não numéricos e limita a 8 dígitos
    var limparValor = cep.value.replace(/\D/g, '').substring(0, 8);

    // Converte o valor limpo em um array de números
    var numerosArray = limparValor.split("");

    var numeroFormatado = "";

    // Adiciona os primeiros 5 dígitos
    if (numerosArray.length > 0) {
        numeroFormatado += numerosArray.slice(0, 5).join("");
    }

    // Adiciona o hífen e os 3 dígitos finais, se existirem
    if (numerosArray.length > 5) {
        numeroFormatado += `-${numerosArray.slice(5, 8).join("")}`;
    }

    // Atualiza o valor no campo de entrada
    cep.value = numeroFormatado;

});

var edit_cep = document.getElementById("edit_cep");
edit_cep.addEventListener("input", () => {

    // Remove todos os caracteres não numéricos e limita a 8 dígitos
    var limparValor = edit_cep.value.replace(/\D/g, '').substring(0, 8);

    // Converte o valor limpo em um array de números
    var numerosArray = limparValor.split("");

    var numeroFormatado = "";

    // Adiciona os primeiros 5 dígitos
    if (numerosArray.length > 0) {
        numeroFormatado += numerosArray.slice(0, 5).join("");
    }

    // Adiciona o hífen e os 3 dígitos finais, se existirem
    if (numerosArray.length > 5) {
        numeroFormatado += `-${numerosArray.slice(5, 8).join("")}`;
    }

    // Atualiza o valor no campo de entrada
    edit_cep.value = numeroFormatado;

});

var celular = document.getElementById("celular");
celular.addEventListener("input", () => {

    var limparValor = celular.value.replace(/\D/g, '').substring(0, 11);

    var numerosArray = limparValor.split("");

    var numeroFormatado = "";

    if (numerosArray.length > 0) {
        numeroFormatado += `(${numerosArray.slice(0, 2).join("")})`;
    }

    if (numerosArray.length > 2) {
        numeroFormatado += ` ${numerosArray.slice(2, 7).join("")}`;
    }

    if (numerosArray.length > 7) {
        numeroFormatado += `-${numerosArray.slice(7, 11).join("")}`;
    }

    celular.value = numeroFormatado;

});

var edit_celular = document.getElementById("edit_celular");
edit_celular.addEventListener("input", () => {

    var limparValor = edit_celular.value.replace(/\D/g, '').substring(0, 11);

    var numerosArray = limparValor.split("");

    var numeroFormatado = "";

    if (numerosArray.length > 0) {
        numeroFormatado += `(${numerosArray.slice(0, 2).join("")})`;
    }

    if (numerosArray.length > 2) {
        numeroFormatado += ` ${numerosArray.slice(2, 7).join("")}`;
    }

    if (numerosArray.length > 7) {
        numeroFormatado += `-${numerosArray.slice(7, 11).join("")}`;
    }

    edit_celular.value = numeroFormatado;

});

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
document.getElementById('cpf').addEventListener('input', function (e) {
    e.target.value = formatarCPF(e.target.value);
});

document.getElementById('edit_cpf').addEventListener('input', function (e) {
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
            // Resetando as modalidades selecionadas no formulário de cadastro
            $('#modalidades').val(null).trigger('change');
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
        document.getElementById("visualizar_celular").innerHTML = resposta['dados'].celular;
        document.getElementById("visualizar_ativo").innerHTML = resposta['dados'].ativo;
        document.getElementById("visualizar_cpf").innerHTML = formatarCPF(resposta['dados'].cpf); // Aplica a formatação
        document.getElementById("visualizar_genero").innerHTML = resposta['dados'].genero;
        document.getElementById("visualizar_modalidade").innerHTML = resposta['dados'].modalidades; // Adiciona as modalidades
        document.getElementById("visualizar_curso").innerHTML = resposta['dados'].curso;
        document.getElementById("visualizar_cep").innerHTML = resposta['dados'].cep;
        document.getElementById("visualizar_bairro").innerHTML = resposta['dados'].bairro;
        document.getElementById("visualizar_rua").innerHTML = resposta['dados'].rua;
        document.getElementById("visualizar_ra").innerHTML = resposta['dados'].ra;
        // Inserção dos dados do local
        document.getElementById("visualizar_estado").innerHTML = resposta.dados.estado_nome || 'Estado não informado';
        document.getElementById("visualizar_cidade").innerHTML = resposta.dados.cidade_nome || 'Cidade não informada';
        document.getElementById("visualizar_cep").innerHTML = resposta['dados'].cep || 'CEP não informado';
        document.getElementById("visualizar_bairro").innerHTML = resposta['dados'].bairro || 'Bairro não informado';
        document.getElementById("visualizar_rua").innerHTML = resposta['dados'].rua || 'Rua não informada';
        document.getElementById("visualizar_numero_resid").innerHTML = resposta['dados'].numero_resid || 'Número não informado';
        document.getElementById("visualizar_complemento").innerHTML = resposta['dados'].complemento || 'Complemento não informado';

        // Seleciona o botão e a div de detalhes do local
        const btnDetalhesLocal = document.getElementById('btnDetalhesLocal');
        const detalhesLocal = document.getElementById('detalhes_local');

        // Variável de controle para saber se os detalhes estão visíveis ou ocultos
        let detalhesLocalVisiveis = false;

        // Função para alternar a visibilidade da div de local
        function toggleLocalDetails() {
            if (!detalhesLocalVisiveis) {
                // Mostrar detalhes do local
                detalhesLocal.style.display = 'block';
                btnDetalhesLocal.textContent = "Ocultar Informações do Local";
                detalhesLocal.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // Ocultar detalhes do local
                detalhesLocal.style.display = 'none';
                btnDetalhesLocal.textContent = "Mostrar Informações do Local";
            }
            // Inverter o estado de visibilidade
            detalhesLocalVisiveis = !detalhesLocalVisiveis;
        }

        // Adicionar o evento ao botão de local
        btnDetalhesLocal.addEventListener('click', toggleLocalDetails);

        // Verifica se os dados do responsável estão disponíveis antes de inseri-los
        document.getElementById("visualizar_nome_responsavel").innerHTML = resposta['dados'].nome_responsavel || 'Nenhum responsável relacionado';
        document.getElementById("visualizar_telefone_responsavel").innerHTML = resposta['dados'].telefone_responsavel || 'Nenhum telefone cadastrado';

        // Seleciona o botão e a div de detalhes do responsável
        const btnDetalhesResponsavel = document.getElementById('btnDetalhesResponsavel');
        const detalhesResponsavel = document.getElementById('detalhes_responsavel');

        // Variável de controle para saber se os detalhes estão visíveis ou ocultos
        let detalhesVisiveis = false;

        // Função para alternar a visibilidade da div
        function toggleDetails() {
            if (!detalhesVisiveis) {
                // Mostrar detalhes
                detalhesResponsavel.style.display = 'block';
                btnDetalhesResponsavel.textContent = "Ocultar Informações do Responsável";
                detalhesResponsavel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // Ocultar detalhes
                detalhesResponsavel.style.display = 'none';
                btnDetalhesResponsavel.textContent = "Mostrar Informações do Responsável";
            }
            // Inverter o estado de visibilidade
            detalhesVisiveis = !detalhesVisiveis;
        }

        // Adicionar o evento ao botão
        btnDetalhesResponsavel.addEventListener('click', toggleDetails);

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
        document.getElementById("edit_celular").value = resposta['dados'].celular;
        document.getElementById("edit_email").value = resposta['dados'].email;
        document.getElementById("edit_ra").value = resposta['dados'].ra;
        document.getElementById("edit_cpf").value = formatarCPF(resposta['dados'].cpf);
        document.getElementById("edit_genero").value = resposta['dados'].genero;
        document.getElementById("edit_curso").value = resposta['dados'].curso;
        document.getElementById("edit_cep").value = resposta['dados'].cep;
        document.getElementById("edit_bairro").value = resposta['dados'].bairro;
        document.getElementById("edit_rua").value = resposta['dados'].rua;
        document.getElementById("edit_numero_resid").value = resposta['dados'].numero_resid;
        document.getElementById("edit_complemento").value = resposta['dados'].complemento;
        document.getElementById("edit_nome_responsavel").value = resposta['dados'].nome_responsavel;
        document.getElementById("edit_telefone_responsavel").value = resposta['dados'].telefone_responsavel;
        document.getElementById("edit_estado").value = resposta.dados.estado_id_rec;

        // Divida as modalidades em um array
        const modalidades_id = resposta['dados'].modalidades_id.split(',').map(id => id.trim());

        console.log(modalidades_id); // Verifica o array de IDs de modalidades

        // Defina os valores selecionados no Select2
        $('#edit_modalidades').val(modalidades_id).trigger('change');
        // Carregar estados
        fetch('get_estados.php')
            .then(response => response.json())
            .then(data => {
                const selectEstado = document.getElementById('edit_estado');
                data.estados.forEach(function (estado) {
                    const option = document.createElement('option');
                    option.value = estado.id;
                    option.textContent = estado.uf;
                    selectEstado.appendChild(option);
                });

                // Selecionar o estado atual
                selectEstado.value = resposta.dados.estado_id_rec;

                // Após selecionar o estado, carregar as cidades automaticamente
                carregarCidades(resposta.dados.estado_id_rec, resposta.dados.cidade_id_rec);
            })
            .catch(error => console.error('Erro ao carregar estados:', error));
    }
}

// Função para carregar cidades e selecionar a cidade correspondente
function carregarCidades(estadoId, cidadeId) {
    const selectCidade = document.getElementById('edit_cidade');
    selectCidade.innerHTML = '<option value="">Selecione a Cidade</option>'; // Limpar opções existentes

    if (estadoId) {
        fetch('get_cidades.php?estado_id=' + estadoId)
            .then(response => response.json())
            .then(data => {
                data.cidades.forEach(function (cidade) {
                    const option = document.createElement('option');
                    option.value = cidade.id;
                    option.textContent = cidade.nome;
                    selectCidade.appendChild(option);
                });

                // Selecionar a cidade se estiver definida
                if (cidadeId) {
                    selectCidade.value = cidadeId;
                }
            })
            .catch(error => console.error('Erro ao carregar cidades:', error));

    }
}
// Atualizar cidades quando um estado é selecionado manualmente
document.getElementById('edit_estado').addEventListener('change', function () {
    const estadoId = this.value;
    carregarCidades(estadoId, null); // Carregar cidades sem uma cidade pré-selecionada
});

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
        // Rolar suavemente para o topo
        msgAlertaErroEdit.scrollIntoView({ behavior: 'smooth' });
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
            // Adicione mais cases para outras páginas, se necessário
            default:
                return `
                <p>Bem-vindo à Página de Associados! Aqui estão algumas instruções para ajudá-lo a navegar e utilizar os recursos:</p>
                <ul>
                    <li><strong>Lista de Associados:</strong> Visualize a lista completa de associados, com detalhes como Nome, Curso, E-mail e Modalidades.</li>
                    <li><strong>Botões de Ação:</strong> Em cada linha, você encontra três botões:
                        <ul>
                            <li><i class="fa-regular fa-eye"></i> <strong>Visualizar:</strong> Ver detalhes completos do associado.</li>
                            <li><i class="fa-regular fa-pen-to-square"></i> <strong>Editar:</strong> Modificar as informações do associado.</li>
                            <li><i class="fa-solid fa-trash"></i> <strong>Excluir:</strong> Remover o associado da lista.</li>
                        </ul>
                    </li>
                    <li><strong>Pesquisar:</strong> Use a barra de pesquisa para encontrar rapidamente um associado pelo nome ou e-mail.</li>
                    <li><strong>Cadastrar Novo:</strong> Clique no botão "Cadastrar" para adicionar um novo associado.</li>
                    <li><strong>Paginação:</strong> Navegue pelas páginas usando os botões na parte inferior da tela para visualizar mais associados.</li>
                </ul>
                <p>Utilize esses recursos para gerenciar seus associados de forma simples e eficiente!</p>
                `;
        }
    }
    
});