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
            document.getElementById("visualizar_title").innerText = info.event.title + " de " + info.event.extendedProps.modalidade + " " + info.event.extendedProps.genero;
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

            function atualizaPlacar(placarCasa, placarAdversario) {
                const placarCasaElem = document.getElementById("placar-casa");
                const placarAdversarioElem = document.getElementById("placar-adversario");

                if (placarCasaElem && placarAdversarioElem) {
                    placarCasaElem.innerText = placarCasa !== null && placarCasa !== undefined ? placarCasa : "";
                    placarAdversarioElem.innerText = placarAdversario !== null && placarAdversario !== undefined ? placarAdversario : "";
                }
            }

            if (info.event.extendedProps.placar_casa !== null && info.event.extendedProps.placar_casa !== undefined &&
                info.event.extendedProps.placar_adversario !== null && info.event.extendedProps.placar_adversario !== undefined) {
                atualizaPlacar(info.event.extendedProps.placar_casa, info.event.extendedProps.placar_adversario);
            } else {
                atualizaPlacar("", "");
            }

            document.getElementById("visualizar_start").innerText = info.event.start.toLocaleString();
            document.getElementById("visualizar_end").innerText = info.event.end !== null ? info.event.end.toLocaleString() : info.event.start.toLocaleString();

            //document.getElementById("visualizar_estado").innerText = info.event.extendedProps.estado_uf;
            document.getElementById("visualizar_cidade").innerText = info.event.extendedProps.cidade_nome + " - " + info.event.extendedProps.estado_uf;
            document.getElementById("visualizar_local").innerText = info.event.extendedProps.local;
            document.getElementById("visualizar_cep").innerText = info.event.extendedProps.cep ? info.event.extendedProps.cep : '';
            document.getElementById("visualizar_rua").innerText = info.event.extendedProps.bairro + ", " + info.event.extendedProps.rua + ", " + info.event.extendedProps.numero;
            document.getElementById("visualizar_complemento").innerText = info.event.extendedProps.complemento ? info.event.extendedProps.complemento : '';


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
            document.getElementById("btnCancelar").addEventListener("click", function () {
                resetModal(); // Apenas esconde os inputs e exibe o botão "Adicionar Placar"
            });

            function removerMsgPlacar() {
                setTimeout(() => {
                    document.getElementById('msgPlacarEvento').innerHTML = "";
                }, 3000)
            }

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
                                msgPlacarEvento.innerHTML = `<div class="alert alert-success" role="alert">Placar Salvo com Sucesso!</div>`;
                                atualizaPlacar(placarCasa, placarAdversario); // Use a função atualizaPlacar aqui
                                resetModal(); // Esconde os inputs após salvar
                                removerMsgPlacar();
                            } else {
                                msgPlacarEvento.innerHTML = `<div class="alert alert-danger" role="alert">Erro ao Salvar Placar!</div>`;
                                removerMsgPlacar();
                            }
                        })
                        .catch(error => console.error("Erro:", error));
                } else {
                    msgPlacarEvento.innerHTML = `<div class="alert alert-danger" role="alert">Preencha os dois campos do placar!</div>`;
                    removerMsgPlacar()
                }
            }
            // Adicionar event listener para o botão "Salvar Placar"
            document.getElementById("btnSalvarPlacar").addEventListener("click", function () {
                salvarPlacar();
                calendar.refetchEvents();
            });

            $('#visualizarModal').on('hidden.bs.modal', function () {
                eventDetails.style.display = 'none';
                btnShowDetails.textContent = "Mostrar convocação";
                detalhesVisiveis = false; // Resetar a flag
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
            document.getElementById("edit_estado").value = info.event.extendedProps.id_estado;
            document.getElementById("edit_cep").value = info.event.extendedProps.cep;
            document.getElementById("edit_rua").value = info.event.extendedProps.rua;
            document.getElementById("edit_bairro").value = info.event.extendedProps.bairro;
            document.getElementById("edit_numero").value = info.event.extendedProps.numero;
            document.getElementById("edit_complemento").value = info.event.extendedProps.complemento;
            document.getElementById("edit_local").value = info.event.extendedProps.local;

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

            atualizarAssociadosEdicao(info.event.id);
            const associados_id = info.event.extendedProps.associados_id ?
                info.event.extendedProps.associados_id.split(',').map(id => id.trim()) : [];


            // Atualiza os dados do modal de edição com os associados filtrados
            function atualizarAssociadosEdicao(eventId) {
                const select = document.getElementById('edit_associados');

                // Capturar os IDs dos associados que já estão selecionados
                const selecionados = Array.from(select.options)
                    .filter(option => option.selected)
                    .map(option => option.value);

                // Capturar o gênero e modalidade do formulário
                const genero = document.getElementById('edit_genero').value;
                const modalidade = document.getElementById('edit_modalidade').value;
                const filtrarPorModalidade = document.getElementById('filtrar_por_modalidade').checked; // Adapte o ID do checkbox se necessário

                console.log('Gênero:', genero);
                console.log('Modalidade:', modalidade);
                console.log('Filtrar por Modalidade:', filtrarPorModalidade);

                // Construir a URL da requisição com os parâmetros
                let url = `get_associados.php?evento_id=${eventId}&genero=${encodeURIComponent(genero)}`;
                if (filtrarPorModalidade) {
                    url += `&modalidade=${encodeURIComponent(modalidade)}`;
                }

                console.log('URL:', url); // Adicione esta linha para verificar a URL da requisição

                // Enviar requisição ao PHP com os parâmetros de gênero e modalidade
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Dados recebidos:', data);

                        select.innerHTML = ''; // Limpar as opções existentes

                        // Manter as opções previamente selecionadas
                        selecionados.forEach(associadoId => {
                            const option = document.createElement('option');
                            option.value = associadoId;
                            option.textContent = data.find(associado => associado.id === associadoId)?.nome || '';
                            option.selected = true;
                            select.appendChild(option);
                        });

                        // Adicionar as novas opções de associados filtrados
                        data.forEach(associado => {
                            if (!selecionados.includes(associado.id)) {
                                const option = document.createElement('option');
                                option.value = associado.id;
                                option.textContent = associado.nome;
                                select.appendChild(option);
                            }
                        });

                        // Reinicializar o Select2 para refletir as mudanças
                        $('#edit_associados').select2({
                            theme: 'bootstrap-5',
                            placeholder: "Selecione os associados",
                            closeOnSelect: false,
                            width: '100%'
                        });

                        $('#edit_associados').val(associados_id).trigger('change');
                    })
                    .catch(error => console.error('Erro ao buscar associados:', error));
            }
            // Adicionar eventos para chamar atualizarAssociados quando o gênero, modalidade ou checkbox mudar
            document.getElementById('edit_genero').addEventListener('change', atualizarAssociadosEdicao);
            document.getElementById('edit_modalidade').addEventListener('change', atualizarAssociadosEdicao);
            document.getElementById('filtrar_por_modalidade').addEventListener('change', atualizarAssociadosEdicao);



            const btnShowDetails = document.getElementById('btnShowDetails');
            const eventDetails = document.getElementById('eventDetails');
            const eventDetailsContent = document.getElementById('eventDetailsContent');
            const associados = (info && info.event && info.event.extendedProps && info.event.extendedProps.associados) || 'Nenhum associado relacionado';

            // Variável de controle para saber se os detalhes estão visíveis ou ocultos
            let detalhesVisiveis = false;

            function toggleDetails() {
                if (!detalhesVisiveis) {
                    // Mostrar detalhes
                    eventDetails.style.display = 'block';
                    eventDetailsContent.innerText = associados;
                    btnShowDetails.textContent = "Ocultar convocação";
                    eventDetails.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                } else {
                    // Ocultar detalhes
                    eventDetails.style.display = 'none';
                    btnShowDetails.textContent = "Mostrar convocação";
                }
                // Inverter o estado de visibilidade
                detalhesVisiveis = !detalhesVisiveis;
            }

            // Adicionar o evento ao botão
            if (btnShowDetails && eventDetails && eventDetailsContent) {
                btnShowDetails.addEventListener('click', toggleDetails);
            }


            // Divida as modalidades em um array

            console.log(associados_id); // Verifica o array de IDs de associados_id

            // Defina os valores selecionados no Select2
            //$('#edit_associados').val(associados_id).trigger('change');


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
                // Rolar o modal para o topo para que o usuário veja a mensagem de erro
                const modal = document.querySelector('#cadastrarModal');
                modal.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

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
                        associados_id: resposta['associados_id'],  // Se disponível
                        cidade_id: resposta['cidade_id'],
                        cidade_nome: resposta['cidade_nome'],
                        estado_nome: resposta['estado_nome'],
                        estado_uf: resposta['estado_uf'],
                        rua: resposta['rua'],
                        cep: resposta['cep'],
                        bairro: resposta['bairro'],
                        numero: resposta['numero'],
                        complemento: resposta['complemento'],
                        local: resposta['local'],
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

                const modal = document.querySelector('#visualizarModal');
                modal.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

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
    // Função para atualizar a lista de associados no modal de edição
    /*function atualizarAssociadosEdicao() {

        const genero = document.getElementById('edit_genero').value;
        const modalidade = document.getElementById('edit_modalidade').value;
        const filtrarPorModalidade = document.getElementById('filtrar_por_modalidade').checked; // Verificar se o checkbox está marcado
        const select = document.getElementById('edit_associados');

        // Capturar os IDs dos associados que já estão selecionados
        const selecionadosIds = Array.from(select.options)
            .filter(option => option.selected)
            .map(option => option.value);

        // Construir a URL da requisição dependendo se o filtro de modalidade está ativado
        let url = `get_associados.php?genero=${encodeURIComponent(genero)}`;
        if (filtrarPorModalidade) {
            url += `&modalidade=${encodeURIComponent(modalidade)}`;
        }

        // Enviar requisição ao PHP com os parâmetros de gênero e modalidade (se aplicável)
        fetch(url)
            .then(response => response.json())
            .then(data => {
                select.innerHTML = ''; // Limpar as opções existentes

                // Manter as opções previamente selecionadas
                selecionadosIds.forEach(id => {
                    const optionExistente = Array.from(select.options).find(option => option.value === id);
                    if (!optionExistente) {
                        const associadoPreviamenteSelecionado = data.find(associado => associado.id === id);
                        if (associadoPreviamenteSelecionado) {
                            const option = document.createElement('option');
                            option.value = associadoPreviamenteSelecionado.id;
                            option.textContent = associadoPreviamenteSelecionado.nome;
                            option.selected = true;
                            select.appendChild(option);
                        }
                    }
                });

                // Adicionar as novas opções de associados filtrados
                data.forEach(associado => {
                    if (!selecionadosIds.includes(associado.id)) {
                        const option = document.createElement('option');
                        option.value = associado.id;
                        option.textContent = associado.nome;
                        select.appendChild(option);
                    }
                });

                // Reinicializar o Select2 para refletir as mudanças
                $('#edit_associados').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Selecione os associados",
                    closeOnSelect: false,
                    width: '100%'
                });
            })
            .catch(error => console.error('Erro ao buscar associados:', error));
    }

    // Adicionar eventos para chamar atualizarAssociados quando o gênero, modalidade ou checkbox mudar
    document.getElementById('edit_genero').addEventListener('change', atualizarAssociadosEdicao);
    document.getElementById('edit_modalidade').addEventListener('change', atualizarAssociadosEdicao);
    document.getElementById('filtrar_por_modalidade').addEventListener('change', atualizarAssociadosEdicao);

    // Forçar a atualização dos associados ao abrir o modal de edição
    document.getElementById('visualizarModal').addEventListener('show.bs.modal', function () {
        atualizarAssociadosEdicao();
    });*/
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
                return '<p>Instruções gerais...</p>';
        }
    }
});
