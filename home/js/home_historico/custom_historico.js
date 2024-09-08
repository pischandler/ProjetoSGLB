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
        let backgroundColor = ''; // Cor padrão
        let textColor = ''; // Cor padrão

        


        if (event.placar_casa !== null && event.placar_adversario !== null) {
            if (event.placar_casa > event.placar_adversario) {
                backgroundColor = '#008000';  // Vitória
                textColor = 'white';  // Texto branco para maior contraste
            } else if (event.placar_casa < event.placar_adversario) {
                backgroundColor = '#B22222';  // Derrota
                textColor = 'white';  // Texto branco para contraste em fundo vermelho
            } else {
                backgroundColor = '#6c757d';  // Empate
                textColor = 'white';  // Texto preto em empate
            }
        }
    
        switch (event.adversario) { // Assuming 'event.adversario' contains the adversary name
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
        <div class="historico-card-events" data-id="${event.id}" style="cursor: pointer; background-color: ${backgroundColor}; color: ${textColor};">
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
                <div class="end-time">
                                <div class="row mb-1"></div>
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
                                console.log(eventDetails.placar_casa);
                                
                                if (eventDetails.placar_casa !== null && eventDetails.placar_adversario !== null) {
                                    document.getElementById("visualizar_placar").innerText = eventDetails.placar_casa + " - " + eventDetails.placar_adversario;
                                } else {
                                    document.getElementById("visualizar_placar").innerText = ""; // Limpa o campo se o placar não estiver disponível
                                }

                                //document.getElementById('visualizar_associados').innerText = eventDetails.associados || 'Nenhum associado relacionado';
                                //document.getElementById("visualizar_modalidade").innerText = eventDetails.modalidade

                                document.getElementById('visualizar_start_historico').innerText = formatDate(eventDetails.start);
                                document.getElementById('visualizar_end_historico').innerText = formatDate(eventDetails.end);

                                const btnPlacar = document.getElementById("btnPlacar");
                                const endDate = eventDetails.end ? new Date(eventDetails.end) : new Date(eventDetails.start);
                                const now = new Date();
                                const placarCasa = eventDetails.placar_casa;
                                const placarAdversario = eventDetails.placar_adversario;
                            
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
                    
                        if (placarCasaInput) placarCasaInput.value = (eventDetails.placar_casa !== null) ? eventDetails.placar_casa : '';
                        if (placarAdversarioInput) placarAdversarioInput.value = (eventDetails.placar_adversario !== null) ? eventDetails.placar_adversario : '';
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
                        const eventId = document.getElementById("visualizar_id_historico").innerText;
                    
                        if (placarCasa !== "" && placarAdversario !== "") {
                            document.getElementById("btnSalvarPlacar").disabled = true;
                    
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
                    
                                        // Atualizar os valores de eventDetails
                                        eventDetails.placar_casa = placarCasa;
                                        eventDetails.placar_adversario = placarAdversario;
                    
                                        document.getElementById("visualizar_placar").innerText = placarCasa + " - " + placarAdversario;
                    
                                        // Atualizar o gráfico de vitórias/derrotas
                                        atualizarGrafico();
                                        loadEvents();
                                        resetModal(); // Esconde os inputs após salvar
                                    } else {
                                        alert("Erro ao salvar o placar.");
                                    }
                                })
                                .catch(error => console.error("Erro:", error))
                                .finally(() => {
                                    document.getElementById("btnSalvarPlacar").disabled = false;
                                });
                        } else {
                            alert("Por favor, preencha ambos os placares.");
                        }
                    }
                    
                                // Adicionar event listener para o botão "Salvar Placar"
                                document.getElementById("btnSalvarPlacar").addEventListener("click", function() {
                                    salvarPlacar();
                                    console.log(eventDetails.placar_casa);
                                });
                    
                                $('#visualizarModal').on('hidden.bs.modal', function () {
                                    resetModal(); // Reseta os inputs sempre que o modal for fechado
                                });

                                const btnEditEvento = document.getElementById('btnViewEditEvento_historico');
                                btnEditEvento.style.display = 'block';

                                visualizarModal.show();

                                // Resetar modal ao fechar
                                visualizarModalElement.addEventListener('hidden.bs.modal', function () {
                                    resetModal(); // Reseta os inputs sempre que o modal for fechado
                                    document.querySelector('#modalBody_historico').style.display = 'block';
                                    document.getElementById('editarEvento_historico').style.display = 'none';
                                    document.querySelector('#modalDialog_historico').style.maxWidth = ''; // Reseta o tamanho do modal
                                });

                                btnEditEvento.addEventListener('click', function () {
                                    const adjustTimeForModal = (time) => {
                                        const date = new Date(time);
                                        return date.toISOString().slice(0, 16); // Retorna no formato YYYY-MM-DDTHH:MM
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

                                        fetch('editar_jogos_historico.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status) {
                                                    alert(data.msg);
                                                    visualizarModal.hide(); // Recarregar os eventos para refletir as mudanças
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
