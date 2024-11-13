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
            <div class="event-card-events" data-id="${event.id}" style="background-color: ${event.color}; color: #fff;cursor: pointer;">
                <h3>${event.title}</h3>
                <div class="event-dates">
                    <div class="start-time">
                        <strong>Início: </strong> ${formattedStartDate}
                    </div>
                    <div class="end-time">
                        <strong>Fim: </strong> ${formattedEndDate}
                    </div>
                </div>
                <span class="local"><strong>${event.cidade_nome} - ${event.estado_uf}</strong></br><strong>${event.local}</strong></br>${event.rua}, ${event.numero}</span>
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

                    document.getElementById('goToEventsPage').addEventListener('click', function () {
                        window.location.href = '/eventos';
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
                                document.getElementById("visualizar_estado").innerText = eventDetails.estado_nome;
                                document.getElementById("visualizar_cidade").innerText = eventDetails.cidade_nome;
                                document.getElementById('visualizar_cep').innerText = eventDetails.cep;
                                document.getElementById('visualizar_rua').innerText = eventDetails.rua;
                                document.getElementById('visualizar_bairro').innerText = eventDetails.bairro;
                                document.getElementById('visualizar_complemento').innerText = eventDetails.complemento;
                                document.getElementById('visualizar_numero').innerText = eventDetails.numero;
                                document.getElementById('visualizar_local').innerText = eventDetails.local;

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
                                    document.getElementById('edit_cep').value = eventDetails.cep;
                                    document.getElementById('edit_rua').value = eventDetails.rua;
                                    document.getElementById('edit_bairro').value = eventDetails.bairro;
                                    document.getElementById('edit_complemento').value = eventDetails.complemento;
                                    document.getElementById('edit_numero').value = eventDetails.numero;
                                    document.getElementById('edit_local').value = eventDetails.local;
                                    document.getElementById("edit_estado").value = eventDetails.id_estado;
                                    // Carregar as cidades com base no estado selecionado
                                    var estadoId = eventDetails.id_estado;
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
                                                document.getElementById("edit_cidade").value = eventDetails.id_cidade;
                                            })
                                            .catch(error => console.error('Erro ao carregar cidades:', error));
                                    }


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
                return `
                <h5>Página Inicial - Dashboard</h5>
                <p>
                    Bem-vindo ao seu painel! Esta página exibe um resumo dos jogos e eventos recentes e futuros, além de um gráfico que ilustra o desempenho das partidas recentes. Abaixo, estão as instruções para entender e navegar pelo dashboard.
                </p>
                
                <h6>1. Histórico de Jogos</h6>
                <p>
                    A seção de <b>Histórico de Jogos</b> mostra os jogos realizados nos últimos 30 dias:
                    <ul>
                        <li>Os <b>cards</b> representam cada jogo, com uma cor que indica o resultado:
                            <ul>
                                <li><b>Verde</b> para vitórias</li>
                                <li><b>Vermelho</b> para derrotas</li>
                                <li><b>Cinza</b> para empates</li>
                                <li><b>Branco</b> caso o placar ainda não tenha sido registrado</li>
                            </ul>
                        </li>
                        <li>Clique em um card para visualizar mais detalhes do jogo, incluindo adversários, data e resultado.</li>
                    </ul>
                </p>
                
                <h6>2. Gráfico de Desempenho</h6>
                <p>
                    O <b>Gráfico de Jogos</b> exibe o desempenho recente em partidas:
                    <ul>
                        <li>Este gráfico de pizza mostra a proporção de <b>vitórias</b>, <b>derrotas</b> e <b>empates</b> entre os jogos realizados.</li>
                        <li>Use este gráfico para analisar o desempenho da equipe ao longo dos últimos jogos.</li>
                    </ul>
                </p>

                <h6>3. Próximos Jogos</h6>
                <p>
                    A seção de <b>Próximos Jogos</b> mostra os jogos futuros:
                    <ul>
                        <li>Se houver jogos marcados, os próximos eventos serão exibidos em forma de cards.</li>
                        <li>Se não houver jogos futuros agendados, será exibida a mensagem "<b>Nenhum evento próximo encontrado</b>" e um link para ir até a página de marcação de jogos.</li>
                        <li>É possível clicar nos cards de jogos futuros para visualizar os detalhes.</li>
                    </ul>
                </p>

                <h6>4. Próximos Eventos</h6>
                <p>
                    A seção de <b>Próximos Eventos</b> mostra os eventos que estão por vir:
                    <ul>
                        <li>Assim como nos jogos, se houver eventos futuros, eles aparecerão em forma de cards.</li>
                        <li>Caso não haja eventos futuros, a mensagem "<b>Nenhum evento próximo encontrado</b>" será exibida, com um link para a página de marcação de eventos.</li>
                        <li>Você pode clicar nos cards de eventos futuros para visualizar mais informações.</li>
                    </ul>
                </p>

                <p>
                    Este painel facilita o acompanhamento das atividades esportivas recentes e futuras, além de fornecer uma visão clara do desempenho da equipe. Caso tenha alguma dúvida, entre em contato com o administrador do sistema.
                </p>
            `;
            default:
                return `
                <h5>Página Inicial - Dashboard</h5>
                <p>
                    Bem-vindo ao seu painel! Esta página exibe um resumo dos jogos e eventos recentes e futuros, além de um gráfico que ilustra o desempenho das partidas recentes. Abaixo, estão as instruções para entender e navegar pelo dashboard.
                </p>
                
                <h6>1. Histórico de Jogos</h6>
                <p>
                    A seção de <b>Histórico de Jogos</b> mostra os jogos realizados nos últimos 30 dias:
                    <ul>
                        <li>Os <b>cards</b> representam cada jogo, com uma cor que indica o resultado:
                            <ul>
                                <li><b>Verde</b> para vitórias</li>
                                <li><b>Vermelho</b> para derrotas</li>
                                <li><b>Cinza</b> para empates</li>
                                <li><b>Branco</b> caso o placar ainda não tenha sido registrado</li>
                            </ul>
                        </li>
                        <li>Clique em um card para visualizar mais detalhes do jogo, incluindo adversários, data e resultado.</li>
                    </ul>
                </p>
                
                <h6>2. Gráfico de Desempenho</h6>
                <p>
                    O <b>Gráfico de Jogos</b> exibe o desempenho recente em partidas:
                    <ul>
                        <li>Este gráfico de pizza mostra a proporção de <b>vitórias</b>, <b>derrotas</b> e <b>empates</b> entre os jogos realizados.</li>
                        <li>Use este gráfico para analisar o desempenho da equipe ao longo dos últimos jogos.</li>
                    </ul>
                </p>

                <h6>3. Próximos Jogos</h6>
                <p>
                    A seção de <b>Próximos Jogos</b> mostra os jogos futuros:
                    <ul>
                        <li>Se houver jogos marcados, os próximos eventos serão exibidos em forma de cards.</li>
                        <li>Se não houver jogos futuros agendados, será exibida a mensagem "<b>Nenhum evento próximo encontrado</b>" e um link para ir até a página de marcação de jogos.</li>
                        <li>É possível clicar nos cards de jogos futuros para visualizar os detalhes.</li>
                    </ul>
                </p>

                <h6>4. Próximos Eventos</h6>
                <p>
                    A seção de <b>Próximos Eventos</b> mostra os eventos que estão por vir:
                    <ul>
                        <li>Assim como nos jogos, se houver eventos futuros, eles aparecerão em forma de cards.</li>
                        <li>Caso não haja eventos futuros, a mensagem "<b>Nenhum evento próximo encontrado</b>" será exibida, com um link para a página de marcação de eventos.</li>
                        <li>Você pode clicar nos cards de eventos futuros para visualizar mais informações.</li>
                    </ul>
                </p>

                <p>
                    Este painel facilita o acompanhamento das atividades esportivas recentes e futuras, além de fornecer uma visão clara do desempenho da equipe. Caso tenha alguma dúvida, entre em contato com o administrador do sistema.
                </p>
            `;
        }
    }
});
