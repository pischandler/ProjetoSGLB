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
                return `
                    <p>Bem-vindo à página de login.</p>
                    <p>Digite seu nome de usuário e senha nos campos correspondentes para acessar o sistema. Selecione a opção <strong>"Lembrar de mim"</strong> caso deseje que o sistema guarde suas credenciais para futuros acessos automáticos.</p>
                    <p>Se esqueceu sua senha, clique em <strong>"Esqueceu sua Senha?"</strong> para recuperar o acesso. Caso ainda não tenha uma conta, clique em <strong>"Cadastrar"</strong> para criar uma nova conta.</p>
                `;
            case 'cadastrar.php':
                return `
                    <p>Bem-vindo à página de cadastro.</p>
                    <p>Preencha os campos com seu primeiro nome, sobrenome, e-mail, nome de usuário e senha para criar uma nova conta. Após preencher todos os dados, clique em <strong>"Salvar"</strong> para concluir o cadastro.</p>
                    <p>Se já possui uma conta, clique em <strong>"Voltar para página de login"</strong> para retornar e realizar o login.</p>
                `;
            case 'forget.php':
                return `
                    <p>Bem-vindo à página de recuperação de senha.</p>
                    <p>Se você esqueceu sua senha, envie um e-mail para o endereço de suporte informado, mencionando que deseja recuperar a senha e inclua o e-mail cadastrado no sistema.</p>
                    <p>Após enviar o e-mail, aguarde as instruções de recuperação. Caso lembre a senha ou queira tentar novamente, clique em <strong>"Voltar para página de login"</strong> para retornar à página inicial.</p>
                `;
                default:
                    return `
                    <p>Bem-vindo à página de login.</p>
                    <p>Digite seu nome de usuário e senha nos campos correspondentes para acessar o sistema. Selecione a opção <strong>"Lembrar de mim"</strong> caso deseje que o sistema guarde suas credenciais para futuros acessos automáticos.</p>
                    <p>Se esqueceu sua senha, clique em <strong>"Esqueceu sua Senha?"</strong> para recuperar o acesso. Caso ainda não tenha uma conta, clique em <strong>"Cadastrar"</strong> para criar uma nova conta.</p>
                `;
        }
    }
});