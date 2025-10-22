// Espera a que todo el contenido del DOM esté cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', () => {
    // ---- Partículas animadas ----
    function createParticles() {

        // Selecciona el contenedor donde se insertarán las partículas
        const particlesContainer = document.getElementById('particles');

        // Si no existe el contenedor, se detiene
        if (!particlesContainer) return;

        // Número total de partículas a crear
        const particleCount = 20;

        // Crea múltiples partículas según el número definido
        for (let i = 0; i < particleCount; i++) {

            // Crea un nuevo div
            const particle = document.createElement('div');

            // Le asigna la clase CSS 'particle'
            particle.className = 'particle';

            // Tamaño aleatorio entre 2px y 7px
            particle.style.width = Math.random() * 5 + 2 + 'px';
            particle.style.height = particle.style.width;

            // Posición horizontal aleatoria en porcentaje (de 0% a 100%)
            particle.style.left = Math.random() * 100 + '%';

            // Retraso y duración aleatorios para que no se muevan todas igual
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (Math.random() * 20 + 20) + 's';

            // Finalmente, añade la partícula al contenedor
            particlesContainer.appendChild(particle);
        }
    }

    // Ejecuta la función para crear las partículas
    createParticles();

    // ---- Alternar entre mostrar y editar datos ----
    const mostrarDatos = document.getElementById('mostrar-datos');
    const editarDatos = document.getElementById('editar-datos');
    const btnCambiarDatos = document.getElementById('btn-cambiar-datos');
    const btnVolverDatos = document.getElementById('btn-volver-datos');

    // Solo se ejecuta si existen los elementos en el DOM
    if (btnCambiarDatos && btnVolverDatos && mostrarDatos && editarDatos) {
        // Cuando el usuario hace clic en "Cambiar datos"
        btnCambiarDatos.addEventListener('click', () => {
            mostrarDatos.style.display = 'none'; // Oculta la sección de vista
            editarDatos.style.display = 'flex'; // Muestra el formulario de edición
        });

        // Cuando el usuario hace clic en "Volver"
        btnVolverDatos.addEventListener('click', () => {
            editarDatos.style.display = 'none'; // Oculta el formulario
            mostrarDatos.style.display = 'flex'; // Muestra de nuevo los datos
        });
    }

    // ---- Smooth scroll en enlaces internos ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault(); // Evita el salto instantáneo
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                // Desplaza suavemente hasta la sección destino
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ---- Efecto Parallax en el hero ----
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        // Aquí se puede agregar efectos parallax si los necesitas
    });

    // ---- Menú hamburguesa ----
    const menuToggle = document.getElementById('menu-toggle'); // Botón ☰ o ✕
    const headerLinks = document.getElementById('header-links'); // Lista de enlaces
    const body = document.body;
    if (menuToggle && headerLinks) {
        // Al hacer clic en el icono del menú
        menuToggle.addEventListener('click', () => {
            headerLinks.classList.toggle('show'); // Muestra/oculta el menú lateral
            menuToggle.textContent = headerLinks.classList.contains('show') ? '✕' : '☰'; // Cambia el icono
            body.classList.toggle('no-scroll'); // Evita el scroll cuando el menú está abierto
        });

        // Cierra el menú cuando el usuario selecciona un enlace
        document.querySelectorAll('#header-links a').forEach(link => {
            link.addEventListener('click', () => {
                headerLinks.classList.remove('show');
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            });
        });

        // Cierra el menú si el usuario hace clic fuera de él
        document.addEventListener('click', (e) => {
            if (!headerLinks.contains(e.target) && !menuToggle.contains(e.target) && headerLinks.classList.contains('show')) {
                headerLinks.classList.remove('show'); 
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            }
        });
    }
});