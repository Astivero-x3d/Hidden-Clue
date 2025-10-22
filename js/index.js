document.addEventListener('DOMContentLoaded', () => {
    // ---- Partículas animadas ----
    function createParticles() {
        // Seleccionamos el contenedor donde estarán las partículas
        const particlesContainer = document.getElementById('particles');
        if (!particlesContainer) return; // Salir si no existe el contenedor
        
        // Cantidad de partículas
        const particleCount = 20;
        
        // Creamos las partículas
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div'); // Creamos div
            particle.className = 'particle'; // Añadimos clase CSS para estilos/animación
            particle.style.width = Math.random() * 5 + 2 + 'px'; // Ancho aleatorio entre 2 y 7px
            particle.style.height = particle.style.width; // Altura igual al ancho
            particle.style.left = Math.random() * 100 + '%'; // Posición horizontal aleatoria
            particle.style.animationDelay = Math.random() * 20 + 's'; // Retardo de animación aleatorio
            particle.style.animationDuration = (Math.random() * 20 + 20) + 's'; // Duración animación aleatoria (20-40s)
            particlesContainer.appendChild(particle); //Añadimos el contenedor
        }
    }

    //Ejecutamos la función al cargar la página
    createParticles();

    // ---- Smooth scroll en enlaces internos ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault(); // Evitar comportamiento predeterminado del enlace
            const target = document.querySelector(this.getAttribute('href')); // Obtener elemento destino
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth' // Scroll suave
                });
            }
        });
    });

    // ---- Efecto Parallax en el hero ----
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset; // Posición actual del scroll
        const hero = document.querySelector('.hero'); // Seleccionamos la sección hero
        if (hero) {
            hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            // Movemos el hero verticalmente a la mitad del scroll
            // Esto genera un efecto parallax
        }
    });

    // ---- Menú hamburguesa ----
    const menuToggle = document.getElementById('menu-toggle'); // Botón menú
    const headerLinks = document.getElementById('header-links'); // Contenedor del menú
    const body = document.body; // Body de la página

    // Abrir/cerrar menú al hacer clic
    if (menuToggle && headerLinks) {
        menuToggle.addEventListener('click', () => {
            headerLinks.classList.toggle('show'); // Mostrar/ocultar menú
            menuToggle.textContent = headerLinks.classList.contains('show') ? '✕' : '☰';
            body.classList.toggle('no-scroll'); // Prevenir scroll de fondo
        });

        // Cerrar menú al hacer clic en un link
        document.querySelectorAll('#header-links a').forEach(link => {
            link.addEventListener('click', () => {
                headerLinks.classList.remove('show'); // Ocultar menú
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll'); // Habilitar scroll
            });
        });

        // Cerrar menú al hacer clic fuera del menú y botón
        document.addEventListener('click', (e) => {
            if (!headerLinks.contains(e.target) && !menuToggle.contains(e.target) && headerLinks.classList.contains('show')) {
                headerLinks.classList.remove('show'); // Ocultar menú
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll'); // Habilitar scroll
            }
        });
    }
});