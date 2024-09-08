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
            document.getElementById("visualizar_id").innerText = info.event.id;
            document.getElementById("visualizar_title").innerText = info.event.title + " de " + info.event.extendedProps.modalidade;
            document.getElementById("visualizar_adversario").innerText = info.event.extendedProps.adversario;
            document.getElementById("visualizar_genero").innerText = info.event.extendedProps.genero;

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
            } else if (adversario === "Coringaco") {
                logoAdversarioElement.src = "../assets/logo_coringaco.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "XV") {
                logoAdversarioElement.src = "../assets/logo_XV.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "A.A.A.Z") {
                logoAdversarioElement.src = "../assets/logo_AAAZ.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Hunters") {
                logoAdversarioElement.src = "../assets/logo_hunters.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Troia") {
                logoAdversarioElement.src = "../assets/logo_troia.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Direito") {
                logoAdversarioElement.src = "../assets/logo_direito.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Caoticos") {
                logoAdversarioElement.src = "../assets/logo_caoticos.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Javas") {
                logoAdversarioElement.src = "../assets/logo_javas.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Gorilas") {
                logoAdversarioElement.src = "../assets/logo_gorilas.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Hornets") {
                logoAdversarioElement.src = "../assets/logo_hornets.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "XIX") {
                logoAdversarioElement.src = "../assets/logo_XIX.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
                logoAdversarioElement.style.height = "115px"; // Exibe a imagem
                logoAdversarioElement.style.paddingTop = "15px"; // Exibe a imagem
                logoAdversarioElement.style.paddingLeft = "17px"; // Exibe a imagem
            } else if (adversario === "A.A.I.J") {
                logoAdversarioElement.src = "../assets/logo_AAAIJ.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else if (adversario === "Soberana") {
                logoAdversarioElement.src = "../assets/logo_soberana.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            } else {
                logoAdversarioElement.src = "../assets/logo_default.png"; // Caminho da logo da Capetada
                logoAdversarioElement.style.display = "block"; // Exibe a imagem
                adversarioElement.style.display = "none"; // Oculta o nome
            }

// Função para resetar o modal
/*function resetModal() {
    const inputPlacar = document.getElementById("inputPlacar");
    const btnPlacar = document.getElementById("btnPlacar");

    if (inputPlacar) {
        inputPlacar.style.display = "none";
    }
    if (btnPlacar) {
        btnPlacar.style.display = "block";
    }

    // Resetar os valores dos inputs
    const placarCasaInput = document.getElementById("placar_casa");
    const placarAdversarioInput = document.getElementById("placar_adversario");

    if (placarCasaInput) placarCasaInput.value = '';
    if (placarAdversarioInput) placarAdversarioInput.value = '';
}

// Função para exibir os inputs de placar com valores existentes, se disponíveis
function exibirInputPlacar() {
    const inputPlacar = document.getElementById("inputPlacar");
    const btnPlacar = document.getElementById("btnPlacar");

    if (inputPlacar) {
        inputPlacar.style.display = "block"; // Exibe os inputs de placar
    }
    if (btnPlacar) {
        btnPlacar.style.display = "none"; // Esconde o botão "Adicionar Placar" ao exibir os inputs
    }

    // Definir os valores dos inputs se não forem NULL
    const placarCasaElem = document.getElementById("visualizar_placar_casa");
    const placarAdversarioElem = document.getElementById("visualizar_placar_adversario");

    const placarCasaInput = document.getElementById("placar_casa");
    const placarAdversarioInput = document.getElementById("placar_adversario");

    if (placarCasaElem && placarCasaInput) {
        const placarCasa = placarCasaElem.innerText.trim();
        placarCasaInput.value = (placarCasa && placarCasa !== "NULL") ? placarCasa : '';
    }
    if (placarAdversarioElem && placarAdversarioInput) {
        const placarAdversario = placarAdversarioElem.innerText.trim();
        placarAdversarioInput.value = (placarAdversario && placarAdversario !== "NULL") ? placarAdversario : '';
    }
}

// Função para salvar o placar
function salvarPlacar() {
    const placarCasa = document.getElementById("placar_casa").value;
    const placarAdversario = document.getElementById("placar_adversario").value;
    const eventId = document.getElementById("visualizar_id").innerText;

    if (placarCasa !== "" && placarAdversario !== "") {
        fetch('salvar_placar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: eventId,
                    placar_casa: placarCasa,
                    placar_adversario: placarAdversario
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert("Placar salvo com sucesso!");
                    document.getElementById("visualizar_placar").innerText = placarCasa + " - " + placarAdversario;
                    resetModal(); // Esconde os inputs após salvar
                } else {
                    alert("Erro ao salvar o placar.");
                }
            })
            .catch(error => console.error("Erro:", error));
    } else {
        alert("Por favor, preencha ambos os placares.");
    }
}*/
            //document.getElementById('visualizar_associados').innerText = info.event.extendedProps.associados || 'Nenhum associado relacionado';
            //document.getElementById("visualizar_modalidade").innerText = info.event.extendedProps.modalidade
            
            if (info.event.extendedProps.placar_casa !== null && info.event.extendedProps.placar_adversario !== null) {
                document.getElementById("visualizar_placar").innerText = info.event.extendedProps.placar_casa + " - " + info.event.extendedProps.placar_adversario;
            } else {
                document.getElementById("visualizar_placar").innerText = ""; // Limpa o campo se o placar não estiver disponível
            }

            document.getElementById("visualizar_start").innerText = info.event.start.toLocaleString();
            document.getElementById("visualizar_end").innerText = info.event.end !== null ? info.event.end.toLocaleString() : info.event.start.toLocaleString();

            const btnPlacar = document.getElementById("btnPlacar");
            const endDate = info.event.end ? new Date(info.event.end) : new Date(info.event.start);
            const now = new Date();
            const placarCasa = info.event.extendedProps.placar_casa;
            const placarAdversario = info.event.extendedProps.placar_adversario;
        
            // Condições para exibir botão de placar
            if (placarCasa === null && placarAdversario === null && endDate < now) {
                btnPlacar.style.display = "block";
                btnPlacar.innerText = "Adicionar Placar";
            } else if (placarCasa !== null && placarAdversario !== null && endDate < now) {
                btnPlacar.style.display = "block";
                btnPlacar.innerText = "Alterar Placar";
            } else {
                btnPlacar.style.display = "none";
            }
        
            // Função para exibir os inputs de placar com valores existentes, se disponíveis
            // Função para exibir os inputs de placar com valores existentes, se disponíveis
function exibirInputPlacar() {
    const inputPlacar = document.getElementById("inputPlacar");
    const btnPlacar = document.getElementById("btnPlacar");
    const btnSalvarPlacar = document.getElementById("btnSalvarPlacar");

    if (inputPlacar) {
        inputPlacar.style.display = "block"; // Mostra os inputs
    }
    if (btnPlacar) {
        btnPlacar.style.display = "none"; // Esconde o botão "Adicionar Placar"
    }
    if (btnSalvarPlacar) {
        btnSalvarPlacar.style.display = "inline-block"; // Mostra o botão "Salvar Placar"
    }

    // Definir os valores dos inputs se disponíveis
    const placarCasaInput = document.getElementById("placar_casa");
    const placarAdversarioInput = document.getElementById("placar_adversario");

    if (placarCasaInput) placarCasaInput.value = (info.event.extendedProps.placar_casa !== null) ? info.event.extendedProps.placar_casa : '';
    if (placarAdversarioInput) placarAdversarioInput.value = (info.event.extendedProps.placar_adversario !== null) ? info.event.extendedProps.placar_adversario : '';
}

// Função para resetar o modal
function resetModal() {
    const inputPlacar = document.getElementById("inputPlacar");
    const btnPlacar = document.getElementById("btnPlacar");
    const btnSalvarPlacar = document.getElementById("btnSalvarPlacar");
    
    if (inputPlacar) {
        inputPlacar.style.display = "none"; // Esconde os inputs
    }
    if (btnPlacar) {
        btnPlacar.style.display = "block"; // Mostra o botão "Adicionar Placar"
    }
    if (btnSalvarPlacar) {
        btnSalvarPlacar.style.display = "none"; // Esconde o botão "Salvar Placar"
    }

    // Resetar os valores dos inputs
    const placarCasaInput = document.getElementById("placar_casa");
    const placarAdversarioInput = document.getElementById("placar_adversario");

    if (placarCasaInput) placarCasaInput.value = '';
    if (placarAdversarioInput) placarAdversarioInput.value = '';
    //calendar.refetchEvents();
}



// Adicionar event listener para o botão "Adicionar Placar"
document.getElementById("btnPlacar").addEventListener("click", exibirInputPlacar);

// Adicionar event listener para o botão "Cancelar"
document.getElementById("btnCancelar").addEventListener("click", function() {
    resetModal(); // Apenas esconde os inputs e exibe o botão "Adicionar Placar"
});

function salvarPlacar() {
    const placarCasa = document.getElementById("placar_casa").value;
    const placarAdversario = document.getElementById("placar_adversario").value;
    const eventId = document.getElementById("visualizar_id").innerText;

    if (placarCasa !== "" && placarAdversario !== "") {
        fetch('salvar_placar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: eventId,
                    placar_casa: placarCasa,
                    placar_adversario: placarAdversario
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert("Placar salvo com sucesso!");
                    document.getElementById("visualizar_placar").innerText = placarCasa + " - " + placarAdversario;
                    resetModal(); // Esconde os inputs após salvar
                } else {
                    alert("Erro ao salvar o placar.");
                }
            })
            .catch(error => console.error("Erro:", error));
    } else {
        alert("Por favor, preencha ambos os placares.");
    }
}
            // Adicionar event listener para o botão "Salvar Placar"
            document.getElementById("btnSalvarPlacar").addEventListener("click", function() {
                salvarPlacar();
                calendar.refetchEvents();
            });

            $('#visualizarModal').on('hidden.bs.modal', function () {
                resetModal(); // Reseta os inputs sempre que o modal for fechado
            });
            

            // Enviar os dados do evento para o formulário editar
            document.getElementById("edit_id").value = info.event.id;
            document.getElementById("edit_title").value = info.event.title;
            document.getElementById("edit_adversario").value = info.event.extendedProps.adversario;
            document.getElementById("edit_genero").value = info.event.extendedProps.genero;
            document.getElementById("edit_modalidade").value = info.event.extendedProps.modalidade_id;
            document.getElementById("edit_start").value = converterData(info.event.start);
            document.getElementById("edit_end").value = info.event.end !== null ? converterData(info.event.end) : converterData(info.event.start);
            document.getElementById("edit_color").value = info.event.backgroundColor;



            /*const btnPlacar = document.getElementById('btnPlacar');
            const placarSection = document.getElementById('placarSection');
            const placarCasa = document.getElementById('placarCasa');
            const placarAdversario = document.getElementById('placarAdversario');
            var placar_casa = info.event.extendedProps.placar_casa;
            var placar_adversario = info.event.extendedProps.placar_adversario;

            console.log(info.event.end); // Log da data de término

            /*if (btnPlacar) {
                var dataAtual = new Date(); // Data atual
                var eventoInicio = info.event.start; // Data de início do evento
                var eventoFim = info.event.end; // Data de fim do evento

                // Verifica se o evento já passou (seja pelo fim ou, se end for null, pela data de início)
                if ((eventoFim && dataAtual > eventoFim) || (!eventoFim && dataAtual > eventoInicio)) {
                    btnPlacar.style.display = 'block'; // Exibe o botão se o evento já passou

                    // Define o texto do botão com base nos valores dos placares
                    if (placar_casa === null || placar_adversario === null) {
                        btnPlacar.textContent = "Adicionar Placar";
                    } else {
                        btnPlacar.textContent = "Alterar Placar";
                    }

                } else {
                    btnPlacar.style.display = 'none'; // Oculta o botão se o evento ainda não passou
                }

                // Remove qualquer listener anterior do botão
                const novoBtnPlacar = btnPlacar.cloneNode(true);
                btnPlacar.parentNode.replaceChild(novoBtnPlacar, btnPlacar);

                novoBtnPlacar.addEventListener('click', function () {
                    if (placarSection.style.display === 'none' || placarSection.style.display === '') {
                        // Mostrar detalhes
                        placarSection.style.display = 'block';

                        // Adicionar conteúdo adicional aos detalhes
                        placarCasa.innerText = placar_casa;
                        placarAdversario.innerText = placar_adversario;

                        novoBtnPlacar.textContent = "Cancelar";
                    } else {
                        // Ocultar detalhes
                        placarSection.style.display = 'none';

                        // Verifica novamente se o placar já foi adicionado ou se deve adicionar
                        if (placar_casa === null || placar_adversario === null) {
                            novoBtnPlacar.textContent = "Adicionar Placar";
                        } else {
                            novoBtnPlacar.textContent = "Alterar Placar";
                        }
                    }
                });
            }*/


                const btnShowDetails = document.getElementById('btnShowDetails');
                const eventDetails = document.getElementById('eventDetails');
                const eventDetailsContent = document.getElementById('eventDetailsContent');
                const associados = (info && info.event && info.event.extendedProps && info.event.extendedProps.associados) || 'Nenhum associado relacionado';
                
                if (btnShowDetails && eventDetails && eventDetailsContent) {
                    btnShowDetails.addEventListener('click', function () {
                        const isHidden = getComputedStyle(eventDetails).display === 'none';
                
                        if (isHidden) {
                            // Mostrar detalhes
                            eventDetails.style.display = 'block';
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
                        genero: resposta['genero'],
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
                    evento.setExtendedProp('genero', resposta['genero']);
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
