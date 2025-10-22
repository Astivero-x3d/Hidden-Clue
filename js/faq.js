// ======================================================================
// Archivo: faq.js
// Descripción: Controla las animaciones, interacciones del menú,
//              y el comportamiento dinámico de las preguntas frecuentes.
// ======================================================================
document.addEventListener('DOMContentLoaded', () => {
    // ---- Partículas animadas ----
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        if (!particlesContainer) return; // Evita errores si el contenedor no existe
        
        const particleCount = 15; // Número de partículas a generar
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle'; // Clase CSS que define el estilo de cada partícula

            // Tamaño aleatorio entre 2px y 6px
            particle.style.width = Math.random() * 4 + 2 + 'px';
            particle.style.height = particle.style.width;

            // Posición aleatoria dentro de la pantalla
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + 'vh';

            // Retraso y duración aleatoria para que no floten todas igual
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (Math.random() * 20 + 20) + 's';
            particlesContainer.appendChild(particle);
        }
    }
    createParticles();

    // ---- Mostrar/Ocultar respuestas de preguntas frecuentes ----
    document.querySelectorAll('.lapregunta-card').forEach(card => {
        const pregunta = card.querySelector('h2');
        const respuesta = card.querySelector('.larespuesta-card');

        if (pregunta && respuesta) {
            // Cambiamos el cursor para indicar que es clicable
            pregunta.style.cursor = 'pointer';

            pregunta.addEventListener('click', () => {
                // Cerramos todas las demás respuestas abiertas
                document.querySelectorAll('.larespuesta-card').forEach(otherRespuesta => {
                    if (otherRespuesta !== respuesta) {
                        otherRespuesta.classList.remove('active');
                    }
                });

                // Alternamos la respuesta actual
                respuesta.classList.toggle('active');
                
                // Si la respuesta se cierra, restauramos su estilo
                if (!respuesta.classList.contains('active')) {
                    respuesta.style.maxHeight = '0';
                    respuesta.style.padding = '0 1.5rem';
                }
            });
        }
    });

    // ---- Menú hamburguesa ----
    const menuToggle = document.getElementById('menu-toggle');
    const headerLinks = document.getElementById('header-links');
    const body = document.body;

    if (menuToggle && headerLinks) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation(); // Evita que el clic cierre el menú inmediatamente
            headerLinks.classList.toggle('show'); // Muestra u oculta el menú lateral
            menuToggle.textContent = headerLinks.classList.contains('show') ? '✕' : '☰'; // Cambia el icono
            body.classList.toggle('no-scroll'); // Bloquea el scroll del fondo
        });

        // Cierra el menú al hacer clic en cualquier enlace del mismo
        document.querySelectorAll('#header-links a').forEach(link => {
            link.addEventListener('click', () => {
                headerLinks.classList.remove('show');
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            });
        });

        // Cierra el menú al hacer clic fuera del área del menú
        document.addEventListener('click', (e) => {
            if (!headerLinks.contains(e.target) && !menuToggle.contains(e.target)) {
                headerLinks.classList.remove('show');
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            }
        });

        // Evita que el clic dentro del menú lo cierre por error
        headerLinks.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    // ---- Desplazamiento suave (smooth scroll) ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href')); // Busca el destino del enlace
            if (target) {
                e.preventDefault();
                // Hace un desplazamiento suave hacia la sección
                target.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});