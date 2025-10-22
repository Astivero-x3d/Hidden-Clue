document.addEventListener('DOMContentLoaded', () => {
    // ---- Partículas animadas ----
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        if (!particlesContainer) return; // Salir si no existe el contenedor
        
        // Cantidad de partículas
        const particleCount = 20;
        
        // Se generan "divs" aleatorios que simulan partículas
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.width = Math.random() * 5 + 2 + 'px';
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (Math.random() * 20 + 20) + 's';
            particlesContainer.appendChild(particle);
        }
    }

    createParticles();

    // ---- Smooth scroll en enlaces internos ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // ---- Efecto Parallax en el hero ----
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        // Aquí puedes agregar efectos parallax si los necesitas
    });

    // ---- Menú hamburguesa ----
    const menuToggle = document.getElementById('menu-toggle');
    const headerLinks = document.getElementById('header-links');
    const body = document.body;

    // Abrir/cerrar menú
    if (menuToggle && headerLinks) {
        menuToggle.addEventListener('click', () => {
            headerLinks.classList.toggle('show');
            menuToggle.textContent = headerLinks.classList.contains('show') ? '✕' : '☰';
            body.classList.toggle('no-scroll');
        });

        // Cerrar menú al hacer clic en un link
        document.querySelectorAll('#header-links a').forEach(link => {
            link.addEventListener('click', () => {
                headerLinks.classList.remove('show');
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            });
        });

        // Cerrar menú al hacer clic fuera de él
        document.addEventListener('click', (e) => {
            if (!headerLinks.contains(e.target) && !menuToggle.contains(e.target) && headerLinks.classList.contains('show')) {
                headerLinks.classList.remove('show');
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            }
        });
    }

    // ---- Validación de formulario ----
    const form = document.getElementById("loginForm");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");

    form.addEventListener("submit", (e) => {
        let valid = true;

        // Resetear errores
        emailError.textContent = "";
        passwordError.textContent = "";

        // Validar email
        if (emailInput.value.trim() === "") {
            emailError.textContent = "Por favor, introduce tu correo electrónico.";
            valid = false;
        }

        // Validar contraseña
        if (passwordInput.value.trim() === "") {
            passwordError.textContent = "Por favor, introduce tu contraseña.";
            valid = false;
        }

        if (!valid) {
            e.preventDefault(); // No enviar formulario si hay errores
        }
    });
});