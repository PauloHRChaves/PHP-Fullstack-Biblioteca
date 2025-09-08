document.addEventListener("DOMContentLoaded", async () => {
    try {
        // Inicia as duas requisições em paralelo
        const [headerResponse, loginResponse] = await Promise.all([
            fetch("/Frontend/header.html"),
            fetch('http://localhost:8000/Backend/src/logged-in.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
        ]);

        // Processa a resposta do header
        const headerHtml = await headerResponse.text();
        const headerPlaceholder = document.getElementById("header-placeholder");
        headerPlaceholder.innerHTML = headerHtml;

        // Processa a resposta do login
        if (loginResponse.ok) {
            document.body.classList.add('is-logged-in');
        } else {
            document.body.classList.add('is-logged-out');
        }

        // Exibe o header após ambas as requisições serem concluídas
        const headerContainer = document.querySelector('.header-container');
        if (headerContainer) {
            headerContainer.classList.add('show');
        }

        // Lógica para ativar o link do menu
        let links = document.querySelectorAll(".nav-link");
        let path = window.location.pathname;

        links.forEach(link => {
            link.classList.remove("active");
            if (link.getAttribute("href") === path) {
                link.classList.add("active");
            }
        });

    } catch (error) {
        console.error('Erro ao carregar recursos:', error);
        document.body.classList.add('is-logged-out');
        const headerContainer = document.querySelector('.header-container');
        if (headerContainer) {
            headerContainer.classList.add('show');
        }
    }
});