const startButton = document.querySelector("#start-button");
const showGameButton = document.querySelector("#show-game");
const gameContainer = document.querySelector(".game");
const dino = document.querySelector(".dino");
const cacto = document.querySelector(".cacto");
const score = document.querySelector(".score");
let alreadyJump = false;
let count = 0;
let gameStarted = false;
let gameOver = false;
let cactoInterval;
let cactoSpeed = 5; // Velocidade inicial do cacto
let speedIncreaseRate = 0.05; // Taxa de aumento de velocidade a cada intervalo
let speedIncreaseInterval = 1000; // Tempo em milissegundos para aumentar a velocidade

startButton.addEventListener("click", () => {
  resetGame();
  gameContainer.style.display = "block";
  startButton.style.display = "none";
  gameStarted = true;
  gameOver = false;
  moveCacto(); // Inicia o movimento do cacto ao iniciar o jogo
  increaseSpeed(); // Inicia o aumento gradual da velocidade
});

showGameButton.addEventListener("click", () => {
  gameContainer.style.display = "block"; // Mostra o jogo
  startButton.style.display = "block"; // Mostra o botão Start
  showGameButton.style.display = "none"; // Oculta o botão de mostrar jogo

  // Rola automaticamente até o final da página
  window.scrollTo({
    top: document.body.scrollHeight,
    behavior: 'smooth'
  });
});

document.addEventListener("keydown", (e) => {
  if (gameStarted && !gameOver && ((e.code === "ArrowUp") || (e.code === "Space"))) {
    jump();
  }
});

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

function moveCacto() {
  cacto.style.left = "500px"; // Reseta a posição inicial do cacto
  cactoInterval = setInterval(() => {
    if (!gameStarted || gameOver) return;

    let cactoLeft = parseInt(window.getComputedStyle(cacto).getPropertyValue("left"));
    let dinoBottom = parseInt(window.getComputedStyle(dino).getPropertyValue("bottom"));

    // Move o cacto para a esquerda com base na velocidade atual
    cacto.style.left = (cactoLeft - cactoSpeed) + "px";

    // Verifica colisão
    if (cactoLeft > 40 && cactoLeft < 270 && dinoBottom <= 50 && !alreadyJump) {
      alert(`Game Over! Seu score foi: ${count}`);
      gameOver = true;
      gameStarted = false;
      startButton.style.display = "block";
      clearInterval(cactoInterval); // Para o movimento do cacto
    }

    // Reseta a posição do cacto se sair da tela
    if (cactoLeft <= -60) {
      cacto.style.left = "500px";
    }

    count++;
    score.innerHTML = `SCORE: ${count}`;
  }, 20); // Ajuste o intervalo para controlar a atualização do cacto
}

function increaseSpeed() {
  setInterval(() => {
    if (!gameStarted || gameOver) return;

    cactoSpeed += speedIncreaseRate; // Aumenta a velocidade do cacto
  }, speedIncreaseInterval);
}

function resetGame() {
  count = 0;
  score.innerHTML = `SCORE: ${count}`;
  dino.style.bottom = "0px"; // Reseta a posição do Dino
  cacto.style.left = "500px"; // Reseta a posição do Cacto
  cactoSpeed = 5; // Velocidade inicial mais lenta
  clearInterval(cactoInterval); // Para o movimento do cacto, se houver
}
