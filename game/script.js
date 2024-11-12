const dino = document.querySelector(".dino");
const cacto = document.querySelector(".cacto");
const score = document.querySelector(".score");
const startMessage = document.querySelector(".start-message"); // Seleciona o título
let alreadyJump = false;
let count = 0;
let gameStarted = false;
let gameInterval;

document.addEventListener("keydown", (e) => {
  if (e.code === "Space" && !gameStarted) {
    startGame(); // Inicia o jogo ao pressionar "Espaço"
  }

  if ((e.code === "ArrowUp" || e.code === "Space") && gameStarted) {
    jump();
  }
});

document.addEventListener("DOMContentLoaded", function() {
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

function startGame() {
  gameStarted = true;
  startMessage.style.display = "none"; // Esconde a mensagem ao iniciar o jogo
  
  gameInterval = setInterval(() => {
    let dinoBottom = parseInt(
      window.getComputedStyle(dino).getPropertyValue("bottom")
    );
    let cactoLeft = parseInt(
      window.getComputedStyle(cacto).getPropertyValue("left")
    );

    if (cactoLeft > 40 && cactoLeft < 270 && dinoBottom <= 50 && !alreadyJump) {
      alert(`Game Over! Seu score foi: ${count}`);
      count = 0;
      clearInterval(gameInterval); // Para o jogo após o fim
      gameStarted = false;
      startMessage.style.display = "block"; // Mostra a mensagem novamente após o "Game Over"
    }

    count++;
    score.innerHTML = `SCORE: ${count}`;
  }, 10);
}

function jump() {
  if (!dino.classList.contains("jump")) {
    dino.classList.add("jump");
    alreadyJump = true;

    setTimeout(() => {
      dino.classList.remove("jump");
      alreadyJump = false;
    }, 1100);
  }
}
