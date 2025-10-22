// =========================================================
// Archivo: salas.js
// Descripción: Controla la lógica de interacción de la página salas.php.
// Incluye animaciones visuales, filtros dinámicos con AJAX,
// paginación, scroll suave y el menú hamburguesa responsive.
// =========================================================
document.addEventListener('DOMContentLoaded', () => {
    // ---- Partículas animadas ----
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        if (!particlesContainer) return; // Salir si no existe el contenedor
        // Cantidad de partículas
        const particleCount = 20; // Cantidad de partículas a generar
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

    // Llamada inicial para crear las partículas
    createParticles();

    // ---- Smooth scroll en enlaces internos ----
    // Evita saltos bruscos al hacer clic en un enlace que lleva a un id (#)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth' // Desplazamiento animado
                });
            }
        });
    });

    // ---- Efecto Parallax en el hero ----
    // Preparado para aplicar efectos visuales si se desea
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        // Aquí puedes agregar efectos parallax si los necesitas
    });

    // ---- Filtros, ordenación y paginación AJAX ----
    // Referencia al formulario de filtros
    const form = document.getElementById('form-filtros');

    // Función principal: carga las salas según filtros, búsqueda u orden
    function cargarSalas(pagina = 1) {
        // Obtiene los valores actuales de los campos
        const busqueda = document.getElementById('busqueda').value;
        const dificultad = document.getElementById('dificultad').value;
        const orden = document.getElementById('orden').value;
        // Crea los parámetros de consulta para la petición AJAX
        const params = new URLSearchParams({
            busqueda,
            dificultad,
            orden,
            pagina
        });

        // Llamada a un script PHP que devuelve HTML dinámico
        fetch('includes/filtracion_ordenacion_salas.php?' + params.toString())
            .then(response => response.text())
            .then(html => {
                // Convierte el HTML recibido en un documento temporal
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Actualiza la lista de salas y la paginación
                document.getElementById('salas-listado').innerHTML = doc.getElementById('salas-listado').innerHTML;
                document.getElementById('paginacion').innerHTML = doc.getElementById('paginacion').innerHTML;
            });
    }


    // Evento paginación (delegado)
    // Detecta clics en los botones de cambio de página generados dinámicamente
    document.getElementById('paginacion').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-pagina')) {
            const pagina = e.target.dataset.pagina;
            cargarSalas(pagina); // Carga la página seleccionada
        }
    });

    // ---- Eventos automáticos al cambiar filtros ----
    // Cuando el usuario cambia búsqueda, dificultad u orden, se actualiza el listado
    ['busqueda', 'dificultad', 'orden'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', () => cargarSalas(1));
        }
    });

    // Evento submit filtros
    const formFiltros = document.getElementById('form-filtros');
    if (formFiltros) {
        formFiltros.addEventListener('submit', function(e) {
            e.preventDefault(); // Evita recargar la página
            cargarSalas(1); // Recarga resultados filtrados desde la primera página
        });
    }

    // Cargar salas al inicio
    cargarSalas(1);

    // ---- Menú hamburguesa ----
    const menuToggle = document.getElementById('menu-toggle');
    const headerLinks = document.getElementById('header-links');
    const body = document.body;

    // Abrir/cerrar menú
    if (menuToggle && headerLinks) {
        menuToggle.addEventListener('click', () => {
            headerLinks.classList.toggle('show');
            // Cambia el icono del menú según el estado
            menuToggle.textContent = headerLinks.classList.contains('show') ? '✕' : '☰';
            body.classList.toggle('no-scroll'); // Evita desplazamiento al abrir el menú
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
})();