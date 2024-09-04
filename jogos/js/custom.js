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
            console.log("Evento:", info.event); // Para depuração
            console.log("Modalidade:", info.event.extendedProps.modalidade); // Para depuração
            console.log("Associados:", info.event.extendedProps.associados);

            // Apresentar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "block";
            document.getElementById("visualizarModalLabel").style.display = "block";

            // Ocultar o formulário editar do evento
            document.getElementById("editarEvento").style.display = "none";
            document.getElementById("editarModalLabel").style.display = "none";

            // Enviar para a janela modal os dados do evento
            //document.getElementById("visualizar_id").innerText = info.event.id;
            document.getElementById("visualizar_title").innerText = info.event.title + " de " + info.event.extendedProps.modalidade;
            document.getElementById("visualizar_adversario").innerText = info.event.extendedProps.adversario;

            var adversarioElement = document.getElementById('visualizar_adversario');
            var logoAdversarioElement = document.getElementById('visualizar_logo_adversario');

            var adversario = adversarioElement.textContent;

            if (adversario === "Capetada") {
                logoAdversarioElement.src = "../assets/logo_capetada.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Sharks") {
                logoAdversarioElement.src = "../assets/logo_sharks.blob"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else {
                logoAdversarioElement.src = "../assets/logo_default.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            }

            //document.getElementById('visualizar_associados').innerText = info.event.extendedProps.associados || 'Nenhum associado relacionado';
            //document.getElementById("visualizar_modalidade").innerText = info.event.extendedProps.modalidade
            document.getElementById("visualizar_start").innerText = info.event.start.toLocaleString();
            document.getElementById("visualizar_end").innerText = info.event.end !== null ? info.event.end.toLocaleString() : info.event.start.toLocaleString();

            // Enviar os dados do evento para o formulário editar
            document.getElementById("edit_id").value = info.event.id;
            document.getElementById("edit_title").value = info.event.title;
            document.getElementById("edit_adversario").value = info.event.extendedProps.adversario;
            document.getElementById("edit_modalidade").value = info.event.extendedProps.modalidade_id;
            document.getElementById("edit_start").value = converterData(info.event.start);
            document.getElementById("edit_end").value = info.event.end !== null ? converterData(info.event.end) : converterData(info.event.start);
            document.getElementById("edit_color").value = info.event.backgroundColor;

            const btnShowDetails = document.getElementById('btnShowDetails');
            const eventDetails = document.getElementById('eventDetails');
            const eventDetailsContent = document.getElementById('eventDetailsContent');
            var associados = info.event.extendedProps.associados || 'Nenhum associado relacionado';

            if (btnShowDetails) {
                btnShowDetails.addEventListener('click', function () {
                    if (eventDetails.style.display === 'none' || eventDetails.style.display === '') {
                        // Mostrar detalhes
                        eventDetails.style.display = 'block';
                        // Adicionar conteúdo adicional aos detalhes
                        eventDetailsContent.innerText = associados;
                        btnShowDetails.textContent = "Ocultar convocação";
                    } else {
                        // Ocultar detalhes
                        eventDetails.style.display = 'none';
                        btnShowDetails.textContent = "Mostrar detalhes";
                    }
                });
            }

            // Divida as modalidades em um array
            const associados_id = info.event.extendedProps.associados_id ?
                info.event.extendedProps.associados_id.split(',').map(id => id.trim()) : [];

            console.log(associados_id); // Verifica o array de IDs de associados_id

            // Defina os valores selecionados no Select2
            $('#edit_associados').val(associados_id).trigger('change');


            var visualizarModal = new bootstrap.Modal(document.getElementById('visualizarModal'));
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
            // Receber os dados do formulário
            const dadosForm = new FormData(formCadEvento);

            // Chamar o arquivo PHP responsável por salvar o evento
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
                    adversario: resposta['adversario'],
                    color: resposta['color'],
                    start: resposta['start'],
                    end: resposta['end'],
                    extendedProps: {
                        modalidade: resposta['modalidade'],
                        associados: resposta['associados'],
                        modalidade_id: resposta['modalidade_id'], // Se disponível
                        associados_id: resposta['associados_id']  // Se disponível
                    }
                };


                // Adicionar o evento ao calendário
                calendar.addEvent(novoEvento);

                // Chamar a função para remover a mensagem após 3 segundos
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

        // Aguardar o usuario clicar no botão editar evento
        btnViewEditEvento.addEventListener("click", () => {

            // Ocultar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "none";
            document.getElementById("visualizarModalLabel").style.display = "none";

            // Mostrar o formulário de editar evento
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

    // Receber o SELETOR do botão da janela modal editar evento
    const btnEditEvento = document.getElementById("btnEditEvento");

    // Somente acessa o IF quando existir o SELETOR "formEditEvento"
    if (formEditEvento) {

        // Aguardar o usuario clicar no botão editar
        formEditEvento.addEventListener("submit", async (e) => {

            // Não permitir a atualização da pagina
            e.preventDefault();

            // Apresentar no botão o texto salvando
            btnEditEvento.value = "Salvando...";

            // Receber os dados do formulário
            const dadosForm = new FormData(formEditEvento);

            // Adicionar o campo do associado ao FormData

            // Chamar o arquivo PHP responsável em atualizar o evento
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

                // Atualizar o evento no calendário
                const evento = calendar.getEventById(resposta['id']);
                if (evento) {
                    evento.setProp('title', resposta['title']);
                    evento.setExtendedProp('adversario', resposta['adversario']);
                    evento.setExtendedProp('modalidade', resposta['modalidade']);
                    evento.setExtendedProp('associados', resposta['associados']);
                    evento.setProp('color', resposta['color']);
                    evento.setStart(resposta['start']);
                    evento.setEnd(resposta['end']);
                }

                // Chamar a função para remover a mensagem após 3 segundo
                removerMsg();
                calendar.refetchEvents();
                $('#visualizarModal').modal('hide');
                // Fechar a janela modal
                visualizarModal.hide();
            }

            // Apresentar no botão o texto Editar
            btnEditEvento.value = "Editar";
        });
    }

    // Receber o SELETOR apagar evento
    const btnApagarEvento = document.getElementById("btnApagarEvento");

    // Somente acessa o IF quando existir o SELETOR "formEditEvento"
    if (btnApagarEvento) {

        // Aguardar o usuario clicar no botão apagar
        btnApagarEvento.addEventListener("click", async () => {

            // Exibir uma caixa de diálogo de confirmação
            const confirmacao = window.confirm("Tem certeza de que deseja apagar este evento?");

            // Verificar se o usuário confirmou
            if (confirmacao) {

                // Receber o id do evento
                var idEvento = document.getElementById("visualizar_id").textContent;
                console.log("ID do Evento para Exclusão:", idEvento); // Log para debug

                // Chamar o arquivo PHP responsável em apagar o evento
                try {
                    const dados = await fetch("apagar_evento.php?id=" + idEvento);
                    const resposta = await dados.json();

                    console.log("Resposta do PHP:", resposta); // Log para debug

                    // Acessa o IF quando não apagar com sucesso
                    if (!resposta['status']) {
                        // Enviar a mensagem para o HTML
                        msgViewEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta['msg']}</div>`;
                    } else {
                        // Enviar a mensagem para o HTML
                        msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta['msg']}</div>`;
                        msgViewEvento.innerHTML = "";

                        // Recuperar o evento no FullCalendar
                        const eventoExisteRemover = calendar.getEventById(idEvento);
                        console.log("Evento para Remover:", eventoExisteRemover); // Log para debug

                        // Verificar se encontrou o evento no FullCalendar
                        if (eventoExisteRemover) {
                            // Remover o evento do calendário
                            eventoExisteRemover.remove();
                        }

                        // Chamar a função para remover a mensagem após 3 segundos
                        removerMsg();

                        // Fechar a janela modal
                        $('#visualizarModal').modal('hide');

                    }
                } catch (error) {
                    console.error("Erro ao apagar o evento:", error); // Log para erro
                }
            }
        });
    }
});
