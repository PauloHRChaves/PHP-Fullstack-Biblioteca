// Função para carregar e exibir o cabeçalho
async function loadAndDisplayHeader() {
    try {
        const response = await fetch("/Frontend/header.html");
        if (!response.ok) {
            throw new Error(`Erro HTTP! Status: ${response.status}`);
        }
        const headerHtml = await response.text();
        const headerPlaceholder = document.getElementById("header-placeholder");
        headerPlaceholder.innerHTML = headerHtml;

        return true; 
    } catch (error) {
        console.error('Erro ao carregar o cabeçalho:', error);
        return false;
    }
}
// Função para verificar o status de login do usuário
async function checkLoginStatus() {
    try {
        const response = await fetch('http://localhost:8000/logged-in', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials:'include'
        });

        if (response.ok) {
            document.body.classList.add('is-logged-in');
            document.body.classList.remove('is-logged-out');
            return true;
        } else {
            document.body.classList.add('is-logged-out');
            document.body.classList.remove('is-logged-in');
            return false;
        }
    } catch (error) {
        console.error('Erro ao verificar status de login:', error);
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
    }
}
// Função para lidar com o processo de logout
function setupLogout() {
    const logoutButton = document.getElementById('logout-button');

    // Verifica se o botão de logout existe na página
    if (logoutButton) {
        logoutButton.addEventListener('click', async (event) => {
            event.preventDefault(); // Impede o comportamento padrão de links, se o botão estiver dentro de uma tag <a>

            try {
                // Faz a requisição para o endpoint de logout
                const response = await fetch('http://localhost:8000/logout', {
                    method: 'GET',
                    credentials: 'include' // Essencial para que o cookie de sessão seja enviado
                });

                if (response.ok) {
                    // Se a resposta for 200 (sucesso), desloga o usuário no frontend
                    document.body.classList.remove('is-logged-in');
                    document.body.classList.add('is-logged-out');
                    console.log('Logout bem-sucedido.');

                    // Opcional: Redirecionar para a página inicial ou de login
                    window.location.href = '/Frontend/index.html'; 
                } else {
                    // Em caso de erro, exibe uma mensagem no console
                    const errorData = await response.json();
                    console.error("Erro no logout:", errorData.message);
                }
            } catch (error) {
                // Em caso de falha de rede ou servidor, exibe o erro
                console.error("Erro ao tentar fazer logout:", error);
            }
        });
    }
}

// Função para ativar o link de navegação da página atual
function activateNavLink() {
    const links = document.querySelectorAll(".nav-link");
    const currentPath = window.location.pathname;

    links.forEach(link => {
        // Remove a classe 'active' de todos os links primeiro
        link.classList.remove("active");
        
        // Ativa apenas o link da página atual
        const linkPath = link.getAttribute("href");
        if (linkPath === currentPath || (linkPath === "/Frontend/index.html" && currentPath === "/")) {
            link.classList.add("active");
        }
    });
}

document.addEventListener("DOMContentLoaded", async () => {
    try {
        await Promise.all([
            loadAndDisplayHeader(),
            checkLoginStatus()
        ]);
        
        activateNavLink();

        setupLogout();

        const headerContainer = document.querySelector('.header-container');
        if (headerContainer) {
            headerContainer.classList.add('show');
        }

    } catch (error) {
        console.error('Erro fatal durante a inicialização:', error);
        document.body.classList.add('is-logged-out');
    }
});