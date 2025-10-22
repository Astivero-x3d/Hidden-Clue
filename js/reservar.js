document.addEventListener('DOMContentLoaded', () => {
    // ---- Partículas animadas ----
    function createParticles() {
        const particlesContainer = document.getElementById('particles'); // Contenedor de partículas
        if (!particlesContainer) return;

        const particleCount = 20; // Número de partículas

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.width = Math.random() * 5 + 2 + 'px'; // Tamaño aleatorio
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + '%'; // Posición horizontal aleatoria
            particle.style.animationDelay = Math.random() * 20 + 's'; // Retardo de animación aleatorio
            particle.style.animationDuration = (Math.random() * 20 + 20) + 's'; // Duración de animación aleatoria

            particlesContainer.appendChild(particle);
        }
    }

    createParticles(); // Se ejecuta al cargar la página

    // ---- Smooth scroll en enlaces internos ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    // Desplazamiento suave al ancla
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

    if (menuToggle && headerLinks) {
        // Toggle del menú al hacer click en el botón hamburguesa
        menuToggle.addEventListener('click', () => {
            headerLinks.classList.toggle('show'); // Mostramos menú
            menuToggle.textContent = headerLinks.classList.contains('show') ? '✕' : '☰'; // Cambiamos icono
            body.classList.toggle('no-scroll'); // Evitamos scroll cuando menú abierto
        });

        // Cerramos menú al hacer click en un enlace
        document.querySelectorAll('#header-links a').forEach(link => {
            link.addEventListener('click', () => {
                headerLinks.classList.remove('show');
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            });
        });

        // Cerramos menú al hacer click fuera del menú
        document.addEventListener('click', (e) => {
            if (!headerLinks.contains(e.target) && !menuToggle.contains(e.target) && headerLinks.classList.contains('show')) {
                headerLinks.classList.remove('show');
                menuToggle.textContent = '☰';
                body.classList.remove('no-scroll');
            }
        });
    }

    // ---- FUNCIONALIDAD PARA HORAS DISPONIBLES ----
    const salaSelect = document.getElementById('form-select'); // Select de sala
    const fechaInput = document.getElementById('fech_reserva'); // Fecha de reserva
    const horaInput = document.getElementById('hora_reserva'); // Input hidden con hora seleccionada
    const horasVisualList = document.getElementById('horas-visual-list'); // Contenedor visual de horas
    const horaSeleccionadaText = document.getElementById('hora-seleccionada-text'); // Texto que muestra hora seleccionada

    // Función para cargar horas disponibles
    function cargarHorasDisponibles() {
        const idSala = salaSelect.value;
        const fechaReserva = fechaInput.value;

        // Validar que se haya seleccionado sala y fecha
        if (!idSala || !fechaReserva) {
            if (horasVisualList) {
                horasVisualList.innerHTML = '';
            }
            if (horaSeleccionadaText) {
                horaSeleccionadaText.textContent = "Selecciona una sala y fecha";
                horaSeleccionadaText.style.color = "#666";
            }
            if (horaInput) {
                horaInput.value = "";
            }
            return;
        }

        // Petición POST a PHP para obtener horas disponibles
        fetch('includes/horas_disponibles.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_sala=${idSala}&fecha_reserva=${fechaReserva}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }

                actualizarListaVisualHoras(data.horas);
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                if (horasVisualList) {
                    horasVisualList.innerHTML = '<div style="color: red;">Error al cargar horas</div>';
                }
            });
    }

    // Función para actualizar la lista visual de horas
    function actualizarListaVisualHoras(horas) {
        if (!horasVisualList) return;

        let html = '<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">';

        horas.forEach(horaInfo => {
            let backgroundColor, color, cursor, title;

            if (horaInfo.ocupada) {
                if (horaInfo.motivo === 'reservada') {
                    backgroundColor = '#ff4444';
                    color = '#ffffff';
                    title = 'Hora ya reservada';
                } else {
                    backgroundColor = '#ffa500';
                    color = '#000000';
                    title = 'Hora no disponible (pasada o muy próxima)';
                }
                cursor = 'not-allowed';
            } else {
                backgroundColor = '#44ff44';
                color = '#000000';
                cursor = 'pointer';
                title = 'Hora disponible - Click para seleccionar';
            }

            html += `
            <div class="hora-option" 
                 data-hora="${horaInfo.hora}"
                 data-ocupada="${horaInfo.ocupada}"
                 style="
                    padding: 10px 15px;
                    background-color: ${backgroundColor};
                    color: ${color};
                    border-radius: 5px;
                    cursor: ${cursor};
                    font-weight: bold;
                    text-align: center;
                    min-width: 80px;
                    transition: all 0.3s ease;
                 " 
                 title="${title}">
                ${horaInfo.hora}
            </div>
        `;
        });

        html += '</div>';
        horasVisualList.innerHTML = html;

        // Resetear selección de hora
        if (horaInput) {
            horaInput.value = "";
        }
        if (horaSeleccionadaText) {
            horaSeleccionadaText.textContent = "Ninguna hora seleccionada";
            horaSeleccionadaText.style.color = "#666";
        }

        // Añadir event listeners a las horas disponibles
        document.querySelectorAll('.hora-option').forEach(option => {
            if (option.getAttribute('data-ocupada') === 'false') {
                option.addEventListener('click', function () {
                    const horaSeleccionada = this.getAttribute('data-hora');

                    // Actualizar el input hidden
                    if (horaInput) {
                        horaInput.value = horaSeleccionada;
                    }

                    // Quitar selección anterior
                    document.querySelectorAll('.hora-option').forEach(opt => {
                        opt.style.border = 'none';
                        opt.style.boxShadow = 'none';
                    });

                    // Marcar como seleccionada
                    this.style.border = '2px solid #0066cc';
                    this.style.boxShadow = '0 0 8px rgba(0,102,204,0.5)';

                    if (horaSeleccionadaText) {
                        horaSeleccionadaText.textContent = `Hora seleccionada: ${horaSeleccionada}`;
                        horaSeleccionadaText.style.color = "#0066cc";
                        horaSeleccionadaText.style.fontWeight = "bold";
                    }
                });
            }
        });
    }

    // Event listeners para detectar cambios
    if (salaSelect && fechaInput) {
        salaSelect.addEventListener('change', cargarHorasDisponibles);
        fechaInput.addEventListener('change', cargarHorasDisponibles);
    }

    // Establecer fecha mínima como mañana (hoy no se puede reservar)
    if (fechaInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1); // Fecha mínima: mañana
        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
        fechaInput.min = tomorrowFormatted;
        
        // También establecer la fecha mínima para evitar seleccionar fechas pasadas o hoy
        fechaInput.addEventListener('input', function() {
            const selectedDate = new Date(this.value);
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1); 
            tomorrow.setHours(0, 0, 0, 0);
            
            if (selectedDate < tomorrow) {
                this.value = tomorrowFormatted;
            }
        });
    }
});