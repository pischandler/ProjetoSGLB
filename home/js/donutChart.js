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

    // Função para atualizar o gráfico externamente
    window.atualizarGrafico = function() {
        fetch('listar_jogos.php')
            .then(response => response.json())
            .then(jogos => {
                const { vitorias, derrotas, empates } = contarResultados(jogos);
                renderizarGrafico(vitorias, derrotas, empates);
            })
            .catch(error => console.error('Erro ao buscar jogos:', error));
    };

    // Carregar jogos e gerar o gráfico na inicialização
    atualizarGrafico();
});
