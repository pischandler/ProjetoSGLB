<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Example</title>
    <link rel="stylesheet" href="https://losbravosuepg.com.br/assets/logolb.png" height="100">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        .header {
          width: 100%;
  height: 110px;
  background-color: #d30909;
  /*background-image: linear-gradient(to left, #d20000, #ff0000, #e40000)*/
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 50px;
        }
        .sair {
          text-decoration: none;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/home">
            <img src="https://losbravosuepg.com.br/assets/logolb.png" height="100" alt="Los Bravos" title="Página Inicial.">
        </a>
        <h1 id="header-title"></h1>
        <span id="welcome-message"></span>
        <a href="../login/index.php" class="sair">Sair.</a>
    </header>

    <script>
        // Função para definir o título e a mensagem de boas-vindas
        function setHeaderContent(title, welcomeMessage) {
            if (title) {
                document.getElementById('header-title').textContent = title;
            } else {
                document.getElementById('header-title').style.display = 'none';
            }
            
            if (welcomeMessage) {
                document.getElementById('welcome-message').textContent = welcomeMessage + ", ...";
            } else {
                document.getElementById('welcome-message').style.display = 'none';
            }
        }

        // Exemplo de uso:
        setHeaderContent("Título Exemplo", "Bem-vindo");
    </script>
</body>
</html>
