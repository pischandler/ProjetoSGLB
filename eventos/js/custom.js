// Executar quando o documento HTML for completamente carregado
document.addEventListener('DOMContentLoaded', function () {

    // Receber o SELETOR calendar do atributo id
    var calendarEl = document.getElementById('calendar');

    // Receber o SELETOR da janela modal cadastrar
    const cadastrarModal = new bootstrap.Modal(document.getElementById("cadastrarModal"));

    // Receber o SELETOR da janela modal visualizar
    const visualizarModal = new bootstrap.Modal(document.getElementById("visualizarModal"));

    // Receber o SELETOR "msgViewEvento"
    const msgViewEvento = document.getElementById('msgViewEvento');

    // Instanciar FullCalendar.Calendar e atribuir a variável calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {

        // Incluir o bootstrap 5
        themeSystem: 'bootstrap5',

        // Criar o cabeçalho do calendário
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        // Definir o idioma usado no calendário
        locale: 'pt-br',

        // Definir a data inicial
        //initialDate: '2023-01-12',
        //initialDate: '2023-10-12',

        // Permitir clicar nos nomes dos dias da semana 
        navLinks: true,

        // Permitir clicar e arrastar o mouse sobre um ou vários dias no calendário
        selectable: true,

        // Indicar visualmente a área que será selecionada antes que o usuário solte o botão do mouse para confirmar a seleção
        selectMirror: true,

        // Permitir arrastar e redimensionar os eventos diretamente no calendário.
        editable: true,

        // Número máximo de eventos em um determinado dia, se for true, o número de eventos será limitado à altura da célula do dia
        dayMaxEvents: true,

        // Chamar o arquivo PHP para recuperar os eventos
        events: 'listar_evento.php',

        // Identificar o clique do usuário sobre o evento
        eventClick: function (info) {

            // Apresentar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "block";
            document.getElementById("visualizarModalLabel").style.display = "block";

            // Ocultar o formulário editar do evento
            document.getElementById("editarEvento").style.display = "none";
            document.getElementById("editarModalLabel").style.display = "none";

            // Enviar para a janela modal os dados do evento
            document.getElementById("visualizar_id").innerText = info.event.id;
            document.getElementById("visualizar_title").innerText = info.event.title;
            document.getElementById("visualizar_start").innerText = info.event.start.toLocaleString();
            document.getElementById("visualizar_end").innerText = info.event.end !== null ? info.event.end.toLocaleString() : info.event.start.toLocaleString();
            document.getElementById("visualizar_estado").innerText = info.event.extendedProps.estado_nome;
            document.getElementById("visualizar_cidade").innerText = info.event.extendedProps.cidade_nome;
            document.getElementById("visualizar_cep").innerText = info.event.extendedProps.cep;
            document.getElementById("visualizar_local").innerText = info.event.extendedProps.local;
            document.getElementById("visualizar_bairro").innerText = info.event.extendedProps.bairro;
            document.getElementById("visualizar_rua").innerText = info.event.extendedProps.rua;
            document.getElementById("visualizar_numero").innerText = info.event.extendedProps.numero;
            document.getElementById("visualizar_complemento").innerText = info.event.extendedProps.complemento;

            console.log(info.event.cidade_nome);

            // Enviar os dados do evento para o formulário editar
            document.getElementById("edit_id").value = info.event.id;
            document.getElementById("edit_title").value = info.event.title;
            document.getElementById("edit_start").value = converterData(info.event.start);
            document.getElementById("edit_end").value = info.event.end !== null ? converterData(info.event.end) : converterData(info.event.start);
            document.getElementById("edit_color").value = info.event.backgroundColor;
            document.getElementById("edit_estado").value = info.event.extendedProps.id_estado;
            document.getElementById("edit_cep").value = info.event.extendedProps.cep;
            document.getElementById("edit_local").value = info.event.extendedProps.local;
            document.getElementById("edit_bairro").value = info.event.extendedProps.bairro;
            document.getElementById("edit_rua").value = info.event.extendedProps.rua;
            document.getElementById("edit_numero").value = info.event.extendedProps.numero;
            document.getElementById("edit_complemento").value = info.event.extendedProps.complemento;

            // Carregar as cidades com base no estado selecionado
            var estadoId = info.event.extendedProps.id_estado;
            var selectCidade = document.getElementById('edit_cidade');
            selectCidade.innerHTML = '<option value="">Selecione a Cidade</option>'; // Limpar opções existentes

            if (estadoId) {
                fetch('get_cidades.php?estado_id=' + estadoId)
                    .then(response => response.json())
                    .then(data => {
                        data.cidades.forEach(function (cidade) {
                            var option = document.createElement('option');
                            option.value = cidade.id;
                            option.textContent = cidade.nome;
                            selectCidade.appendChild(option);
                        });

                        // Definir o valor da cidade após o carregamento das opções
                        document.getElementById("edit_cidade").value = info.event.extendedProps.id_cidade;
                    })
                    .catch(error => console.error('Erro ao carregar cidades:', error));
            }

            // Abrir a janela modal visualizar
            visualizarModal.show();
        },






        // Abrir a janela modal cadastrar quando clicar sobre o dia no calendário
        select: function (info) {

            // Chamar a função para converter a data selecionada para ISO8601 e enviar para o formulário
            document.getElementById("cad_start").value = converterData(info.start);
            document.getElementById("cad_end").value = converterData(info.start);

            // Abrir a janela modal cadastrar
            cadastrarModal.show();
        }
    });

    // Renderizar o calendário
    calendar.render();

    // Converter a data
    function converterData(data) {

        // Converter a string em um objeto Date
        const dataObj = new Date(data);

        // Extrair o ano da data
        const ano = dataObj.getFullYear();

        // Obter o mês, mês começa de 0, padStart adiciona zeros à esquerda para garantir que o mês tenha dígitos
        const mes = String(dataObj.getMonth() + 1).padStart(2, '0');

        // Obter o dia do mês, padStart adiciona zeros à esquerda para garantir que o dia tenha dois dígitos
        const dia = String(dataObj.getDate()).padStart(2, '0');

        // Obter a hora, padStart adiciona zeros à esquerda para garantir que a hora tenha dois dígitos
        const hora = String(dataObj.getHours()).padStart(2, '0');

        // Obter minuto, padStart adiciona zeros à esquerda para garantir que o minuto tenha dois dígitos
        const minuto = String(dataObj.getMinutes()).padStart(2, '0');

        // Retornar a data
        return `${ano}-${mes}-${dia} ${hora}:${minuto}`;
    }

    // Receber o SELETOR do formulário cadastrar evento
    const formCadEvento = document.getElementById("formCadEvento");

    // Receber o SELETOR da mensagem genérica
    const msg = document.getElementById("msg");

    // Receber o SELETOR da mensagem cadastrar evento
    const msgCadEvento = document.getElementById("msgCadEvento");

    // Receber o SELETOR do botão da janela modal cadastrar evento
    const btnCadEvento = document.getElementById("btnCadEvento");

    // Somente acessa o IF quando existir o SELETOR "formCadEvento"
    if (formCadEvento) {

        // Aguardar o usuario clicar no botao cadastrar
        formCadEvento.addEventListener("submit", async (e) => {

            // Não permitir a atualização da pagina
            e.preventDefault();

            // Apresentar no botão o texto salvando
            btnCadEvento.value = "Salvando...";

            // Receber os dados do formulário
            const dadosForm = new FormData(formCadEvento);

            // Chamar o arquivo PHP responsável em salvar o evento
            const dados = await fetch("cadastrar_evento.php", {
                method: "POST",
                body: dadosForm
            });

            // Realizar a leitura dos dados retornados pelo PHP
            const resposta = await dados.json();

            // Acessa o IF quando não cadastrar com sucesso
            if (!resposta['status']) {

                // Enviar a mensagem para o HTML
                msgCadEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta['msg']}</div>`;

            } else {

                // Enviar a mensagem para o HTML
                msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta['msg']}</div>`;

                // Enviar a mensagem para o HTML
                msgCadEvento.innerHTML = "";

                // Limpar o formulário
                formCadEvento.reset();

                // Criar o objeto com os dados do evento
                const novoEvento = {
                    id: resposta['id'],
                    title: resposta['title'],
                    color: resposta['color'],
                    start: resposta['start'],
                    end: resposta['end'],
                    extendedProps: {
                        cidade_id: resposta['cidade_id'],
                        cidade_nome: resposta['cidade_nome'],
                        estado_nome: resposta['estado_nome'],
                        rua: resposta['rua'],
                        bairro: resposta['bairro'],
                        local: resposta['local'],
                        cep: resposta['cep'],
                        complemento: resposta['complemento'],
                        numero: resposta['numero'],
                    }
                }
                console.log(novoEvento); // Verifique o conteúdo do objeto
                // Adicionar o evento ao calendário
                calendar.addEvent(novoEvento);

                // Chamar a função para remover a mensagem após 3 segundo
                removerMsg();

                // Fechar a janela modal
                cadastrarModal.hide();
            }

            // Apresentar no botão o texto Cadastrar
            btnCadEvento.value = "Cadastrar";

        });
    }

    // Função para remover a mensagem após 3 segundo
    function removerMsg() {
        setTimeout(() => {
            document.getElementById('msg').innerHTML = "";
        }, 3000)
    }

    // Receber o SELETOR ocultar detalhes do evento e apresentar o formulário editar evento
    const btnViewEditEvento = document.getElementById("btnViewEditEvento");

    // Somente acessa o IF quando existir o SELETOR "btnViewEditEvento"
    if (btnViewEditEvento) {

        // Aguardar o usuario clicar no botao editar
        btnViewEditEvento.addEventListener("click", () => {

            // Ocultar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "none";
            document.getElementById("visualizarModalLabel").style.display = "none";

            // Apresentar o formulário editar do evento
            document.getElementById("editarEvento").style.display = "block";
            document.getElementById("editarModalLabel").style.display = "block";
        });
    }

    // Receber o SELETOR ocultar formulário editar evento e apresentar o detalhes do evento
    const btnViewEvento = document.getElementById("btnViewEvento");

    // Somente acessa o IF quando existir o SELETOR "btnViewEvento"
    if (btnViewEvento) {

        // Aguardar o usuario clicar no botao editar
        btnViewEvento.addEventListener("click", () => {

            // Apresentar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "block";
            document.getElementById("visualizarModalLabel").style.display = "block";

            // Ocultar o formulário editar do evento
            document.getElementById("editarEvento").style.display = "none";
            document.getElementById("editarModalLabel").style.display = "none";
        });
    }

    // Receber o SELETOR do formulário editar evento
    const formEditEvento = document.getElementById("formEditEvento");

    // Receber o SELETOR da mensagem editar evento 
    const msgEditEvento = document.getElementById("msgEditEvento");

    // Receber o SELETOR do botão editar evento
    const btnEditEvento = document.getElementById("btnEditEvento");

    // Somente acessa o IF quando existir o SELETOR "formEditEvento"
    if (formEditEvento) {

        // Aguardar o usuario clicar no botao editar
        formEditEvento.addEventListener("submit", async (e) => {

            // Não permitir a atualização da pagina
            e.preventDefault();

            // Apresentar no botão o texto salvando
            btnEditEvento.value = "Salvando...";

            // Receber os dados do formulário
            const dadosForm = new FormData(formEditEvento);

            // Chamar o arquivo PHP responsável em editar o evento
            const dados = await fetch("editar_evento.php", {
                method: "POST",
                body: dadosForm
            });

            // Realizar a leitura dos dados retornados pelo PHP
            const resposta = await dados.json();

            // Acessa o IF quando não editar com sucesso
            if (!resposta['status']) {

                // Enviar a mensagem para o HTML
                msgEditEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta['msg']}</div>`;
            } else {

                // Enviar a mensagem para o HTML
                msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta['msg']}</div>`;

                // Enviar a mensagem para o HTML
                msgEditEvento.innerHTML = "";

                // Limpar o formulário
                formEditEvento.reset();

                // Recuperar o evento no FullCalendar pelo id 
                const eventoExiste = calendar.getEventById(resposta['id']);

                // Verificar se encontrou o evento no FullCalendar pelo id
                if (eventoExiste) {
                    // Atualizar os atributos do evento com os novos valores do banco de dados
                    eventoExiste.setProp('title', resposta['title']);
                    eventoExiste.setProp('color', resposta['color']);
                    eventoExiste.setStart(resposta['start']);
                    eventoExiste.setEnd(resposta['end']);

                    // Atualizar as propriedades estendidas (se estiver usando)
                    eventoExiste.setExtendedProp('cidade_id', resposta['cidade_id']);
                    eventoExiste.setExtendedProp('cidade_nome', resposta['cidade_nome']);
                    eventoExiste.setExtendedProp('estado_nome', resposta['estado_nome']);
                    eventoExiste.setExtendedProp('estado_uf', resposta['estado_uf']);
                    eventoExiste.setExtendedProp('cep', resposta['cep']);
                    eventoExiste.setExtendedProp('rua', resposta['rua']);
                    eventoExiste.setExtendedProp('bairro', resposta['bairro']);
                    eventoExiste.setExtendedProp('numero', resposta['numero']);
                    eventoExiste.setExtendedProp('complemento', resposta['complemento']);
                    eventoExiste.setExtendedProp('local', resposta['local']);
                }

                // Chamar a função para remover a mensagem após 3 segundo
                removerMsg();

                // Fechar a janela modal
                visualizarModal.hide();
            }

            // Apresentar no botão o texto salvar
            btnEditEvento.value = "Salvar";
        });
    }

    // Receber o SELETOR apagar evento
    const btnApagarEvento = document.getElementById("btnApagarEvento");

    // Somente acessa o IF quando existir o SELETOR "formEditEvento"
    if (btnApagarEvento) {

        // Aguardar o usuario clicar no botao apagar
        btnApagarEvento.addEventListener("click", async () => {

            // Exibir uma caixa de diálogo de confirmação
            const confirmacao = window.confirm("Tem certeza de que deseja apagar este evento?");

            // Verificar se o usuário confirmou
            if (confirmacao) {

                // Receber o id do evento
                var idEvento = document.getElementById("visualizar_id").textContent;

                // Chamar o arquivo PHP responsável apagar o evento
                const dados = await fetch("apagar_evento.php?id=" + idEvento);

                // Realizar a leitura dos dados retornados pelo PHP
                const resposta = await dados.json();

                // Acessa o IF quando não cadastrar com sucesso
                if (!resposta['status']) {

                    // Enviar a mensagem para o HTML
                    msgViewEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta['msg']}</div>`;
                } else {

                    // Enviar a mensagem para o HTML
                    msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta['msg']}</div>`;

                    // Enviar a mensagem para o HTML
                    msgViewEvento.innerHTML = "";

                    // Recuperar o evento no FullCalendar
                    const eventoExisteRemover = calendar.getEventById(idEvento);

                    // Verificar se encontrou o evento no FullCalendar
                    if (eventoExisteRemover) {

                        // Remover o evento do calendário
                        eventoExisteRemover.remove();
                    }

                    // Chamar a função para remover a mensagem após 3 segundo
                    removerMsg();

                    // Fechar a janela modal
                    visualizarModal.hide();

                }
            }
        });

    }

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
            <p>Bem-vindo à Página de Eventos! Aqui você pode gerenciar eventos usando o calendário interativo.</p>
            <ul>
                <li><strong>Visualização do Calendário:</strong> O calendário exibe eventos em formato mensal, semanal ou diário. Use os botões no topo para alternar entre as visualizações.</li>
                <li><strong>Navegação:</strong> As setas permitem navegar entre datas futuras e passadas. O botão "Hoje" retorna para a data atual.</li>
                <li><strong>Adicionar Evento:</strong> Clique em um dia no calendário para abrir o modal de cadastro de evento. Preencha as informações e salve para adicionar o evento.</li>
                <li><strong>Visualizar Evento:</strong> Clique em um evento no calendário para ver seus detalhes. No modal de visualização, você pode ver informações como título, data e local.</li>
                <li><strong>Editar Evento:</strong> No modal de visualização do evento, clique no botão "Editar" para modificar as informações do evento.</li>
                <li><strong>Excluir Evento:</strong> No modal de visualização, clique em "Apagar" para remover o evento do calendário.</li>
            </ul>
            <p>Esses recursos facilitam a organização e o gerenciamento de eventos diretamente no calendário.</p>
            `;
        }
    }
});