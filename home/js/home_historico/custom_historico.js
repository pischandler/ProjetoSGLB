document.addEventListener("DOMContentLoaded", function () {

    fetch('get_associados.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('edit_associados_historico');
            select.innerHTML = ''; // Limpar as opções existentes
            data.forEach(associado => {
                const option = document.createElement('option');
                option.value = associado.id;
                option.textContent = associado.nome;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Erro ao buscar associados:', error));


    const eventCardsContainer = document.getElementById('historico-cards-container');
    const visualizarModalElement = document.getElementById('visualizarModal_historico');
    const visualizarModal = new bootstrap.Modal(visualizarModalElement);

    function createEventCard(event) {
        const startDate = new Date(event.start).toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });
        const endDate = new Date(event.end).toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });
    
        let logoSrc = '';
        let displayAdversario = true;
    
        switch (event.adversario) { // Assuming 'event.adversario' contains the adversary name
            case "Capetada":
                logoSrc = "../assets/logo_capetada.png";
                displayAdversario = false;
                break;
            case "Sharks":
                logoSrc = "../assets/logo_sharks.blob";
                displayAdversario = false;
                break;
            // Add more cases as needed
            default:
                logoSrc = '../assets/logo_default.png';
                displayAdversario = false;
        }
    
        return `
            <div class="historico-card-events" data-id="${event.id}" style="cursor: pointer;">
                <h4>${event.title}</h4>
                <h5>de ${event.modalidade}</h5>
    
                <div class="d-flex align-items-center justify-content-center">
                    <!-- Logo da equipe -->
                    <div class="team">
                        <img src="../assets/imagemLosBravos.png" alt="Los Bravos" class="teamlogo" style="width: 80px; height: 80px;">
                    </div>
    
                    <!-- X no meio -->
                    <div class="versus d-flex align-items-center mx-3" style="font-size: 30px; font-weight: bold;">
                        <span>X</span>
                    </div>
    
                    <!-- Logo do adversário -->
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
                events.sort((a, b) => new Date(b.start) - new Date(a.start));

                const currentDate = new Date();
                const upcomingEvents = events.filter(event => new Date(event.end) < currentDate);

                if (upcomingEvents.length === 0) {
                    eventCardsContainer.innerHTML = `
                    <div class="no-events-message">
                        Nenhum evento próximo encontrado.
                        </br>
                        <button type='button' id='goToJogosPage' class='btn btn-link no-events-button'>Ir até página de jogos.</button>
                    </div>
                `;

                    // Adiciona o manipulador de eventos ao botão
                    document.getElementById('goToJogosPage').addEventListener('click', function () {
                        window.location.href = '/SGLB/jogos';
                    });
                } else {
                    eventCardsContainer.innerHTML = '';
                    upcomingEvents.forEach(event => {
                        const eventCard = createEventCard(event);
                        eventCardsContainer.innerHTML += eventCard;
                    });

                    document.querySelectorAll('.historico-card-events').forEach(card => {
                        card.addEventListener('click', function () {
                            console.log(document.getElementById('visualizar_start_historico'));
                            const eventId = this.getAttribute('data-id');
                            const eventDetails = events.find(e => e.id === parseInt(eventId));

                            if (eventDetails) {
                                document.getElementById("visualizar_id_historico").innerText = eventDetails.id;
                                document.getElementById("visualizar_title_historico").innerText = eventDetails.title + " de " + eventDetails.modalidade;
                                document.getElementById("visualizar_adversario_historico").innerText = eventDetails.adversario;

                                var adversarioElement = document.getElementById('visualizar_adversario_historico');
                                var logoAdversarioElement = document.getElementById('visualizar_logo_adversario_historico');

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

                                //document.getElementById('visualizar_associados').innerText = eventDetails.extendedProps.associados || 'Nenhum associado relacionado';
                                //document.getElementById("visualizar_modalidade").innerText = eventDetails.extendedProps.modalidade

                                document.getElementById('visualizar_start_historico').innerText = new Date(eventDetails.start).toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });
                                document.getElementById('visualizar_end_historico').innerText = new Date(eventDetails.end).toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });

                                const btnEditEvento = document.getElementById('btnViewEditEvento_historico');
                                btnEditEvento.style.display = 'block';

                                visualizarModal.show();

                                // Resetar modal ao fechar
                                visualizarModalElement.addEventListener('hidden.bs.modal', function () {
                                    document.querySelector('#modalBody_historico').style.display = 'block';
                                    document.getElementById('editarEvento_historico').style.display = 'none';
                                    document.querySelector('#modalDialog_historico').style.maxWidth = ''; // Reseta o tamanho do modal
                                });

                                btnEditEvento.addEventListener('click', function () {
                                    const adjustTimeForModal = (time) => {
                                        const date = new Date(time);
                                        const userTimezoneOffset = date.getTimezoneOffset() * 60000;
                                        const adjustedDate = new Date(date.getTime() - userTimezoneOffset);
                                        return adjustedDate.toISOString().slice(0, 16); // Retorna no formato YYYY-MM-DDTHH:MM
                                    };

                                    document.getElementById('editarEvento_historico').style.display = 'block';
                                    document.getElementById('edit_id_historico').value = eventDetails.id;
                                    document.getElementById('edit_title_historico').value = eventDetails.title;
                                    document.getElementById('edit_adversario_historico').value = eventDetails.adversario;
                                    document.getElementById('edit_modalidade_historico').value = eventDetails.modalidade_id;
                                    document.getElementById('edit_start_historico').value = adjustTimeForModal(eventDetails.start);
                                    document.getElementById('edit_end_historico').value = adjustTimeForModal(eventDetails.end);
                                    document.getElementById('edit_color_historico').value = eventDetails.color;

                                    // Divida as modalidades em um array
                                    const modalidade_id = eventDetails.modalidade_id
                                    const associados_id = eventDetails.associados_id ?
                                        eventDetails.associados_id.split(',').map(id => id.trim()) : [];

                                    console.log(associados_id); // Verifica o array de IDs de associados_id
                                    console.log(modalidade_id); // Verifica o array de IDs de associados_id

                                    // Defina os valores selecionados no Select2
                                    $('#edit_associados_historico').val(associados_id).trigger('change');



                                    document.querySelector('#modalBody_historico').style.display = 'none';

                                    // Aumentar o tamanho do modal ao editar
                                    document.querySelector('#modalContent_historico').style.padding = '20px'; // Adiciona padding ao conteúdo

                                    document.getElementById('btnViewEvento_historico').addEventListener('click', function () {
                                        document.querySelector('#modalBody_historico').style.display = 'block';
                                        document.getElementById('editarEvento_historico').style.display = 'none';
                                        //width: 100px;
                                        // Resetar o tamanho do modal
                                        document.querySelector('#modalDialog_historico').style.maxWidth = ''; // Reseta o tamanho do modal
                                        document.querySelector('#modalContent_historico').style.padding = ''; // Reseta o padding
                                    });

                                    document.getElementById('formEditEvento_historico').addEventListener('submit', function (e) {
                                        e.preventDefault();

                                        const formData = new FormData(this);

                                        fetch('editar_jogos.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status) {
                                                    alert(data.msg);
                                                    visualizarModal.hide();
                                                    loadEvents();  // Recarregar os eventos para refletir as mudanças
                                                } else {
                                                    alert(data.msg);
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

    // Receber o SELETOR apagar evento
    const btnApagarEvento = document.getElementById("btnApagarEvento_historico");

    if (btnApagarEvento) {

        btnApagarEvento.addEventListener("click", async () => {
            const confirmacao = window.confirm("Tem certeza de que deseja apagar este evento?");

            if (confirmacao) {
                const idEvento = document.getElementById("visualizar_id_historico").textContent;

                try {
                    const dados = await fetch(`apagar_jogos.php?id=${idEvento}`);
                    const resposta = await dados.json();

                    if (!resposta.status) {
                        const msgViewEvento = document.getElementById('msgViewEvento_historico');
                        if (msgViewEvento) {
                            msgViewEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta.msg}</div>`;
                        }
                    } else {
                        const msg = document.getElementById('msg');
                        if (msg) {
                            msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta.msg}</div>`;
                        }

                        // Chama loadEvents para atualizar a lista
                        loadEvents();

                        const visualizarModal = bootstrap.Modal.getInstance(visualizarModalElement);
                        if (visualizarModal) {
                            visualizarModal.hide();
                        }
                    }

                    // Chamar a função para remover a mensagem após 3 segundos
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

    loadEvents(); // Inicializa a lista de eventos ao carregar a página
});
