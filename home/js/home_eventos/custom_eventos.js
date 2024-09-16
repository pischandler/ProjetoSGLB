document.addEventListener("DOMContentLoaded", function () {
    const eventCardsContainer = document.getElementById('event-cards-container');
    const visualizarModalElement = document.getElementById('visualizarModal');
    const visualizarModal = new bootstrap.Modal(visualizarModalElement);

    function createEventCard(event) {
        const startDate = new Date(event.start);
        let endDate = new Date(event.end);

        // Se a data de fim for nula ou anterior à data de início, ajusta para que seja igual à de início
        if (!event.end || endDate < startDate) {
            endDate = startDate;
        }

        const formattedStartDate = startDate.toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });
        const formattedEndDate = endDate.toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });

        return `
            <div class="event-card-events" data-id="${event.id}" style="background-color: ${event.color}; cursor: pointer;">
                <h3>${event.title}</h3>
                <div class="event-dates">
                    <div class="start-time">
                        <strong>Início:</br></strong> ${formattedStartDate}
                    </div>
                    <div class="end-time">
                        <strong>Fim:</br></strong> ${formattedEndDate}
                    </div>
                </div>
            </div>
        `;
    }

    function loadEvents() {
        fetch('listar_evento.php')
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
                        <button type='button' id='goToEventsPage' class='btn btn-link no-events-button'>Ir até página de eventos.</button>
                    </div>
                `;
                
                document.getElementById('goToEventsPage').addEventListener('click', function() {
                    window.location.href = '/SGLB/eventos';
                });
                } else {
                    eventCardsContainer.innerHTML = '';
                    upcomingEvents.forEach(event => {
                        const eventCard = createEventCard(event);
                        eventCardsContainer.innerHTML += eventCard;
                    });

                    document.querySelectorAll('.event-card-events').forEach(card => {
                        card.addEventListener('click', function () {
                            const eventId = this.getAttribute('data-id');
                            const eventDetails = events.find(e => e.id === parseInt(eventId));

                            if (eventDetails) {
                                const startDate = new Date(eventDetails.start);
                                let endDate = new Date(eventDetails.end);

                                // Verifica se a data de fim é nula ou anterior à de início
                                if (!eventDetails.end || endDate < startDate) {
                                    endDate = startDate;  // Atribui a data de início à data de fim
                                }

                                const formattedStartDate = startDate.toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });
                                const formattedEndDate = endDate.toLocaleString('pt-BR', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' });

                                document.getElementById('visualizar_id').innerText = eventDetails.id;
                                document.getElementById('visualizar_title').innerText = eventDetails.title;
                                document.getElementById('visualizar_start').innerText = formattedStartDate;
                                document.getElementById('visualizar_end').innerText = formattedEndDate;

                                const btnEditEvento = document.getElementById('btnViewEditEvento');
                                btnEditEvento.style.display = 'block';

                                visualizarModal.show();

                                visualizarModalElement.addEventListener('hidden.bs.modal', function () {
                                    document.querySelector('#modalBody').style.display = 'block';
                                    document.getElementById('editarEvento').style.display = 'none';
                                    document.querySelector('#modalDialog').style.maxWidth = '';
                                });

                                btnEditEvento.addEventListener('click', function () {
                                    const adjustTimeForModal = (time) => {
                                        const date = new Date(time);
                                        const userTimezoneOffset = date.getTimezoneOffset() * 60000;
                                        const adjustedDate = new Date(date.getTime() - userTimezoneOffset);
                                        return adjustedDate.toISOString().slice(0, 16);
                                    };

                                    document.getElementById('editarEvento').style.display = 'block';
                                    document.getElementById('edit_id').value = eventDetails.id;
                                    document.getElementById('edit_title').value = eventDetails.title;
                                    document.getElementById('edit_start').value = adjustTimeForModal(eventDetails.start);

                                    // Verifica se a data de fim é nula ou anterior à data de início
                                    let startDate = new Date(eventDetails.start);
                                    let endDate = new Date(eventDetails.end);
                                    if (!eventDetails.end || endDate < startDate) {
                                        endDate = startDate;  // Atribui a data de início à data de fim
                                    }
                                    document.getElementById('edit_end').value = adjustTimeForModal(endDate);

                                    document.getElementById('edit_color').value = eventDetails.color;

                                    document.querySelector('#modalBody').style.display = 'none';

                                    document.querySelector('#modalContent').style.padding = '20px';

                                    document.getElementById('btnViewEvento').addEventListener('click', function () {
                                        document.querySelector('#modalBody').style.display = 'block';
                                        document.getElementById('editarEvento').style.display = 'none';

                                        document.querySelector('#modalDialog').style.maxWidth = '';
                                        document.querySelector('#modalContent').style.padding = '';
                                    });

                                    function removerMsgEdit() {
                                        setTimeout(() => {
                                            document.getElementById('msgEditEvento').innerHTML = "";
                                        }, 3000);
                                    }

                                    document.getElementById('formEditEvento').addEventListener('submit', function (e) {
                                        e.preventDefault();

                                        const formData = new FormData(this);

                                        fetch('editar_evento.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status) {
                                                    msgEditEvento.innerHTML = `<div class="alert alert-success" role="alert">Evento Editado com Sucesso.</div>`;
                                                    removerMsgEdit();
                                                    loadEvents();
                                                } else {
                                                    msgEditEvento.innerHTML = `<div class="alert alert-danger" role="alert">Erro ao Editar Evento.</div>`;
                                                    removerMsgEdit();
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

    const btnApagarEvento = document.getElementById("btnApagarEvento");

    if (btnApagarEvento) {
        btnApagarEvento.addEventListener("click", async () => {
            const confirmacao = window.confirm("Tem certeza de que deseja apagar este evento?");

            if (confirmacao) {
                const idEvento = document.getElementById("visualizar_id").textContent;

                try {
                    const dados = await fetch(`apagar_evento.php?id=${idEvento}`);
                    const resposta = await dados.json();

                    if (!resposta.status) {
                        const msgViewEvento = document.getElementById('msgViewEvento');
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
