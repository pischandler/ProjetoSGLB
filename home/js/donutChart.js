document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('victoryDonutChart').getContext('2d');

    // Função para contar vitórias, derrotas e empates
    function contarResultados(jogos) {
        let vitorias = 0, derrotas = 0, empates = 0;

        jogos.forEach(jogo => {
            if (jogo.placar_casa !== null && jogo.placar_adversario !== null) {
                if (jogo.placar_casa > jogo.placar_adversario) {
                    vitorias++;
                } else if (jogo.placar_casa < jogo.placar_adversario) {
                    derrotas++;
                } else {
                    empates++;
                }
            }
        });

        return { vitorias, derrotas, empates };
    }

    // Função para renderizar o gráfico
    let doughnutChart;

    function renderizarGrafico(vitorias, derrotas, empates) {
        if (doughnutChart) {
            // Atualizar o gráfico existente
            doughnutChart.data.datasets[0].data = [vitorias, derrotas, empates];
            doughnutChart.update();
        } else {
            // Criar um novo gráfico
            doughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Vitórias', 'Derrotas', 'Empates'],
                    datasets: [{
                        label: 'Resultados dos Jogos',
                        data: [vitorias, derrotas, empates],
                        backgroundColor: ['#008000', '#B22222', '#6c757d'],
                        borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        }
                    }
                }
            });
        }
    }

    // Função para filtrar jogos dos últimos 30 dias
    function filtrarJogos(jogos) {
        const currentDate = new Date();
        // Zeramos a hora, minuto, segundo e milissegundo para comparar apenas a data
        currentDate.setHours(0, 0, 0, 0);

        // Definimos a data limite para 30 dias atrás
        const pastLimitDate = new Date();
        pastLimitDate.setDate(currentDate.getDate() - 30);
        pastLimitDate.setHours(0, 0, 0, 0);

        return jogos.filter(jogo => {
            const eventEndDate = new Date(jogo.end);
            eventEndDate.setHours(0, 0, 0, 0);
            return eventEndDate < currentDate && eventEndDate >= pastLimitDate;
        });
    }

    // Função para atualizar o gráfico externamente
    window.atualizarGrafico = function() {
        fetch('listar_jogos.php')
            .then(response => response.json())
            .then(jogos => {
                // Filtra os jogos dos últimos 30 dias
                const jogosFiltrados = filtrarJogos(jogos);
                const { vitorias, derrotas, empates } = contarResultados(jogosFiltrados);
                renderizarGrafico(vitorias, derrotas, empates);
            })
            .catch(error => console.error('Erro ao buscar jogos:', error));
    };

    // Carregar jogos e gerar o gráfico na inicialização
    atualizarGrafico();
});
