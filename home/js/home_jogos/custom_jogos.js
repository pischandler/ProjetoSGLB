document.addEventListener("DOMContentLoaded", function () {

    // Função para atualizar a lista de associados no modal de edição
    function atualizarAssociadosEdicao() {
        const genero = document.getElementById('edit_genero_jogos').value;
        const modalidade = document.getElementById('edit_modalidade_jogos').value;
        const filtrarPorModalidade = document.getElementById('filtrar_por_modalidade').checked; // Verificar se o checkbox está marcado
        const select = document.getElementById('edit_associados_jogos');

        // Capturar os IDs e nomes dos associados que já estão selecionados
        const selecionados = Array.from(select.options)
            .filter(option => option.selected)
            .map(option => ({
                id: option.value,
                nome: option.textContent
            }));

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
                selecionados.forEach(associado => {
                    const option = document.createElement('option');
                    option.value = associado.id;
                    option.textContent = associado.nome; // Manter o nome original
                    option.selected = true;
                    select.appendChild(option);
                });

                // Adicionar as novas opções de associados filtrados
                data.forEach(associado => {
                    if (!selecionados.some(sel => sel.id === associado.id)) {
                        const option = document.createElement('option');
                        option.value = associado.id;
                        option.textContent = associado.nome;
                        select.appendChild(option);
                    }
                });

                // Reinicializar o Select2 para refletir as mudanças
                $('#edit_associados_jogos').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Selecione os associados",
                    closeOnSelect: false,
                    width: '100%'
                });
            })
            .catch(error => console.error('Erro ao buscar associados:', error));
    }

    // Adicionar eventos para chamar atualizarAssociados quando o gênero, modalidade ou checkbox mudar

    document.getElementById('edit_genero_jogos').addEventListener('change', atualizarAssociadosEdicao);
    document.getElementById('edit_modalidade_jogos').addEventListener('change', atualizarAssociadosEdicao);
    document.getElementById('filtrar_por_modalidade').addEventListener('change', atualizarAssociadosEdicao);

    // Forçar a atualização dos associados ao abrir o modal de edição
    document.getElementById('editarEvento_jogos').addEventListener('show.bs.modal', function () {
        atualizarAssociadosEdicao();
    });



    const eventCardsContainer = document.getElementById('jogos-cards-container');
    const visualizarModalElement = document.getElementById('visualizarModal_jogos');
    const visualizarModal = new bootstrap.Modal(visualizarModalElement);

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('pt-BR', {
            timeZone: 'UTC', // Certifique-se de que a data é exibida no UTC
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        });
    }

    function createEventCard(event) {
        const startDate = formatDate(event.start);
        const endDate = formatDate(event.end);

        let logoSrc = '';
        let displayAdversario = true;

        switch (event.adversario) {
            case "Capetada":
                logoSrc = "../assets/logo_capetada.png";
                displayAdversario = false;
                break;
            case "Sharks":
                logoSrc = "../assets/logo_sharks.blob";
                displayAdversario = false;
                break;
            case "Capetada":
                logoSrc = "../assets/logo_capetada.png";
                displayAdversario = false;
                break;
            case "XIX":
                logoSrc = "../assets/logo_XIX.png";
                displayAdversario = false;
                break;
            case "A.A.A.Z":
                logoSrc = "../assets/logo_AAAZ.png";
                displayAdversario = false;
                break;
            case "Caoticos":
                logoSrc = "../assets/logo_caoticos.png";
                displayAdversario = false;
                break;
            case "XV":
                logoSrc = "../assets/logo_XV.png";
                displayAdversario = false;
                break;
            case "Javas":
                logoSrc = "../assets/logo_Javas.png";
                displayAdversario = false;
                break;
            case "Coringaco":
                logoSrc = "../assets/logo_coringaco.png";
                displayAdversario = false;
                break;
            case "Direito":
                logoSrc = "../assets/logo_direito.png";
                displayAdversario = false;
                break;
            case "Gorilas":
                logoSrc = "../assets/logo_gorilas.png";
                displayAdversario = false;
                break;
            case "Hornets":
                logoSrc = "../assets/logo_hornets.png";
                displayAdversario = false;
                break;
            case "Hunters":
                logoSrc = "../assets/logo_hunters.png";
                displayAdversario = false;
                break;
            case "Troia":
                logoSrc = "../assets/logo_troia.png";
                displayAdversario = false;
                break;
            case "Soberana":
                logoSrc = "../assets/logo_soberana.png";
                displayAdversario = false;
                break;
            default:
                logoSrc = '../assets/logo_default.png';
                displayAdversario = true;
        }

        return `
            <div class="jogos-card-events" data-id="${event.id}" style="cursor: pointer;">
                <h4>${event.title}</h4>
                <h5>de ${event.modalidade}</h5>

                <div class="d-flex align-items-center justify-content-center">
                    <div class="team">
                        <img src="../assets/imagemLosBravos.png" alt="Los Bravos" class="teamlogo" style="width: 80px; height: 80px;">
                    </div>
                    <div class="versus d-flex align-items-center mx-3" style="font-size: 30px; font-weight: bold;">
                        <span>X</span>
                    </div>
                    <div class="adversario d-flex align-items-center">
                        ${displayAdversario ? `<span>${event.adversario}</span>` : `<img src="${logoSrc}" alt="Logo do adversário" style="height: 80px;">`}
                    </div>
                </div>

                <div class="event-dates">
                    <div class="row mb-3"></div>
                    <div class="start-time">
                        <strong>Início: </strong> ${startDate}
                    </div>
                    <div class="row mb-1"></div>
                    <div class="end-time">
                        <strong>Fim: </strong> ${endDate}
                    </div>
                </div>
            </div>
        `;
    }


    function loadEvents() {
        fetch('listar_jogos.php')
            .then(response => response.json())
            .then(events => {
                events.sort((b, a) => new Date(b.start) - new Date(a.start));

                const currentDate = new Date();
                const upcomingEvents = events.filter(event => new Date(event.end) > currentDate);

                if (upcomingEvents.length === 0) {
                    eventCardsContainer.innerHTML = `
                    <div class="no-events-message">
                        Nenhum evento próximo encontrado.
                        </br>
                        <button type='button' id='goToJogosPage' class='btn btn-link no-events-button'>Ir até página de jogos.</button>
                    </div>
                `;

                    document.getElementById('goToJogosPage').addEventListener('click', function () {
                        window.location.href = '/SGLB/jogos';
                    });
                } else {
                    eventCardsContainer.innerHTML = '';
                    upcomingEvents.forEach(event => {
                        const eventCard = createEventCard(event);
                        eventCardsContainer.innerHTML += eventCard;
                    });

                    document.querySelectorAll('.jogos-card-events').forEach(card => {
                        card.addEventListener('click', function () {
                            const eventId = this.getAttribute('data-id');
                            const eventDetails = events.find(e => e.id === parseInt(eventId));

                            if (eventDetails) {
                                document.getElementById("visualizar_id_jogos").innerText = eventDetails.id;
                                document.getElementById("visualizar_title_jogos").innerText = eventDetails.title + " de " + eventDetails.modalidade;
                                document.getElementById("visualizar_adversario_jogos").innerText = eventDetails.adversario;



                                var adversarioElement = document.getElementById('visualizar_adversario_jogos');
                                var logoAdversarioElement = document.getElementById('visualizar_logo_adversario_jogos');


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
                                } else if (adversario === "Gorilas") {
                                    logoAdversarioElement.src = "../assets/logo_gorilas.png"; // Caminho da logo da Capetada
                                    logoAdversarioElement.style.display = "block"; // Exibe a imagem
                                    adversarioElement.style.display = "none"; // Oculta o nome
                                } else {
                                    logoAdversarioElement.src = "../assets/logo_default.png"; // Caminho da logo da Capetada
                                    logoAdversarioElement.style.display = "block"; // Exibe a imagem
                                    adversarioElement.style.display = "none"; // Oculta o nome
                                }

                                document.getElementById('visualizar_start_jogos').innerText = formatDate(eventDetails.start);
                                document.getElementById('visualizar_end_jogos').innerText = formatDate(eventDetails.end);


                                const btnShowDetails = document.getElementById('btnShowDetails');
                                const eventDetails1 = document.getElementById('eventDetails1');
                                const eventDetailsContent = document.getElementById('eventDetailsContent');
                                const associados = (eventDetails.associados) || 'Nenhum associado relacionado';

                                if (btnShowDetails && eventDetails1 && eventDetailsContent) {
                                    btnShowDetails.addEventListener('click', function () {
                                        const isHidden = getComputedStyle(eventDetails1).display === 'none';

                                        if (isHidden) {
                                            // Mostrar detalhes
                                            eventDetails1.style.display = 'block';
                                            eventDetailsContent.innerText = associados;
                                            btnShowDetails.textContent = "Ocultar convocação";
                                            // Scroll para o elemento
                                            eventDetails1.scrollIntoView({
                                                behavior: 'smooth', // Faz o scroll suave
                                                block: 'start' // Alinha o elemento ao topo da tela
                                            });
                                        } else {
                                            // Ocultar detalhes
                                            eventDetails1.style.display = 'none';
                                            btnShowDetails.textContent = "Mostrar detalhes";
                                        }
                                    });
                                }

                                const btnEditEvento = document.getElementById('btnViewEditEvento_jogos');
                                btnEditEvento.style.display = 'block';

                                visualizarModal.show();

                                visualizarModalElement.addEventListener('hidden.bs.modal', function () {
                                    document.querySelector('#modalBody_jogos').style.display = 'block';
                                    document.getElementById('editarEvento_jogos').style.display = 'none';
                                    document.querySelector('#modalDialog_jogos').style.maxWidth = '';
                                });

                                btnEditEvento.addEventListener('click', function () {
                                    const adjustTimeForModal = (time) => {
                                        const date = new Date(time);
                                        return date.toISOString().slice(0, 16);
                                    };

                                    document.getElementById('editarEvento_jogos').style.display = 'block';
                                    document.getElementById('edit_id_jogos').value = eventDetails.id;
                                    document.getElementById('edit_title_jogos').value = eventDetails.title;
                                    document.getElementById('edit_adversario_jogos').value = eventDetails.adversario;
                                    document.getElementById('edit_modalidade_jogos').value = eventDetails.modalidade_id;
                                    document.getElementById('edit_start_jogos').value = adjustTimeForModal(eventDetails.start);
                                    document.getElementById('edit_end_jogos').value = adjustTimeForModal(eventDetails.end);
                                    document.getElementById('edit_color_jogos').value = eventDetails.color;

                                    const modalidade_id = eventDetails.modalidade_id;
                                    const associados_id = eventDetails.associados_id ?
                                        eventDetails.associados_id.split(',').map(id => id.trim()) : [];

                                    $('#edit_associados_jogos').val(associados_id).trigger('change');

                                    document.querySelector('#modalBody_jogos').style.display = 'none';

                                    document.querySelector('#modalContent_jogos').style.padding = '20px';

                                    document.getElementById('btnViewEvento_jogos').addEventListener('click', function () {
                                        document.querySelector('#modalBody_jogos').style.display = 'block';
                                        document.getElementById('editarEvento_jogos').style.display = 'none';
                                        document.querySelector('#modalDialog_jogos').style.maxWidth = '';
                                        document.querySelector('#modalContent_jogos').style.padding = '';
                                    });

                                    function removerMsg() {
                                        setTimeout(() => {
                                            document.getElementById('msgEditEvento_jogos').innerHTML = "";
                                        }, 3000)
                                    }

                                    document.getElementById('formEditEvento_jogos').addEventListener('submit', function (e) {
                                        e.preventDefault();

                                        const formData = new FormData(this);

                                        fetch('editar_jogos.php', {
                                            method: 'POST',
                                            body: formData
                                        })


                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status) {
                                                    msgEditEvento_jogos.innerHTML = `<div class="alert alert-success" role="alert">${data.msg}</div>`;
                                                    loadEvents();
                                                    removerMsg();
                                                } else {
                                                    msgEditEvento_jogos.innerHTML = `<div class="alert alert-danger" role="alert">${data.msg}</div>`;
                                                    removerMsg();
                                                }
                                            })
                                            .catch(error => console.error('Erro ao salvar a edição:', error));
                                    });
                                });
                            }
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Erro ao carregar os eventos:', error);
                eventCardsContainer.innerHTML = "<p>Erro ao carregar os eventos.</p>";
            });
    }

    const btnApagarEvento = document.getElementById("btnApagarEvento_jogos");

    if (btnApagarEvento) {
        btnApagarEvento.addEventListener("click", async () => {
            const confirmacao = window.confirm("Tem certeza de que deseja apagar este evento?");

            if (confirmacao) {
                const idEvento = document.getElementById("visualizar_id_jogos").textContent;

                try {
                    const dados = await fetch(`apagar_jogos.php?id=${idEvento}`);
                    const resposta = await dados.json();

                    if (!resposta.status) {
                        const msgViewEvento = document.getElementById('msgViewEvento_jogos');
                        if (msgViewEvento) {
                            msgViewEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta.msg}</div>`;
                        }
                    } else {
                        const msg = document.getElementById('msg');
                        if (msg) {
                            msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta.msg}</div>`;
                        }

                        loadEvents();

                        const visualizarModal = bootstrap.Modal.getInstance(visualizarModalElement);
                        if (visualizarModal) {
                            visualizarModal.hide();
                        }
                    }

                    setTimeout(() => {
                        const msg = document.getElementById('msg');
                        if (msg) {
                            msg.innerHTML = "";
                        }
                    }, 3000);
                } catch (error) {
                    console.error('Erro ao apagar o evento:', error);
                }
            }
        });
    }

    loadEvents();
});
