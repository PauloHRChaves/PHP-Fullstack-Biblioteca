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
    const token = localStorage.getItem('authToken');

    if (!token) {
        document.body.classList.add('is-logged-out');
        document.body.classList.remove('is-logged-in');
        return false;
    }

    try {
        const response = await fetch('http://localhost:8000/logged-in', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.ok) {
            document.body.classList.add('is-logged-in');
            document.body.classList.remove('is-logged-out');
            return true;
        } else if (response.status === 401) {
            localStorage.removeItem('authToken');
            document.body.classList.add('is-logged-out');
            document.body.classList.remove('is-logged-in');
            return false;
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

    if (logoutButton) {
        logoutButton.addEventListener('click', (event) => {
            event.preventDefault();

            // Remove o token do localStorage
            localStorage.removeItem('authToken');
            window.location.href = '/Frontend/index.html';
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

        const profileLink = document.getElementById('auth-link');
        if (profileLink) {
            profileLink.addEventListener('click', (event) => {
                event.preventDefault();
                const token = localStorage.getItem('authToken');
                if (token) {
                    window.location.href = '/Frontend/templates/profile.html';
                } else {
                    showToast();
                }
            });
        }
        
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