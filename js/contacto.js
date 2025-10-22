// =========================================================
// ARCHIVO: contacto.js
// Descripción: Funciones de interacción para la página de
// contacto de HiddenClue, incluyendo animación de partículas,
// menú hamburguesa, smooth scroll y validación de email.
// =========================================================

document.addEventListener('DOMContentLoaded', () => {
    // ---- Partículas animadas ----
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        if (!particlesContainer) return; // Sale si no existe el contenedor
        
        // Número de partículas a generar
        const particleCount = 20;
        
        // Generamos partículas con tamaños, posiciones y animaciones aleatorias
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';

            // Tamaño aleatorio entre 2px y 7px
            particle.style.width = Math.random() * 5 + 2 + 'px';
            particle.style.height = particle.style.width;

            // Posición horizontal aleatoria
            particle.style.left = Math.random() * 100 + '%';

            // Retraso aleatorio de animación para que las partículas no se muevan todas al mismo tiempo
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (Math.random() * 20 + 20) + 's';
            particlesContainer.appendChild(particle);
        }
    }

    createParticles();

    // ---- Smooth scroll en enlaces internos ----

    // (document.querySelectorAll('a[href^="#"]')) Selecciona todos los enlaces internos que apuntan a IDs de la misma página
    // "anchor" representa cada enlace interno en el bucle
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            // Obtiene el valor del atributo href del enlace clicado para identificar el elemento objetivo
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                // Desplaza suavemente la página hasta el elemento objetivo
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // ---- Efecto Parallax en el hero ----

    // Detecta el scroll de la página. "scrolled" contiene la distancia en píxeles desde el top.
    // Se puede usar para crear efectos parallax en elementos al desplazarse
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
    });

    // ---- Menú hamburguesa ----
    const menuToggle = document.getElementById('menu-toggle');
    const headerLinks = document.getElementById('header-links');
    const body = document.body;

    // Abrir/cerrar menú
    if (menuToggle && headerLinks) {
        menuToggle.addEventListener('click', () => {
            // Alterna la clase 'show' para mostrar u ocultar el menú hamburguesa
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

        // ---- Validación de email específico ----
        const contactoForm = document.getElementById('contactoForm');
        if (contactoForm) {
            contactoForm.addEventListener('submit', function(e) {
                const emailInput = document.getElementById('email');
                const emailError = document.getElementById('emailError');
                // Toma el valor del input email y elimina espacios al inicio y final
                const emailValue = emailInput.value.trim();
                // Expresión regular básica para validar el formato del email
                const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
                if (!emailValue || !emailRegex.test(emailValue)) {
                    e.preventDefault();
                    emailError.textContent = 'Por favor, introduce un correo electrónico válido.';
                    emailError.style.color = 'red';
                    emailInput.focus();
                } else {
                    emailError.textContent = '';
                }
            });
        }
});