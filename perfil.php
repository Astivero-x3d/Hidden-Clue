<?php
// =========================================================
// Archivo: perfil.php
// Descripción: Página de perfil de usuario de HiddenClue.
// Mostramos los datos del usuario, permitimos editarlos
// y mostramos las reservas realizadas.
// =========================================================
session_start(); // Inicia o reanuda la sesión para acceder a los datos del usuario

// =========================================================
// Si no hay sesión iniciada, redirigimos al login
// Esto protege la página para que solo usuarios autenticados puedan verla
// =========================================================
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <!-- Hacemos que la página se adapte a diferentes pantallas (móvil, tablet, PC) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Perfil</title>

    <!-- ===================== Estilos y recursos externos ===================== -->
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="icon" type="image/x-icon" href="img/Logo_Hidden_Clue.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <!-- ===================== Encabezado: Logo y navegación ===================== -->
    <header>
        <div class="header-container">
            <!-- Mostramos el logo con enlace al inicio -->
            <a href="index.php" class="logo">
                <img src="img/Logo_Hidden_Clue.png" alt="Logo HiddenClue" style="width:80px; height:80px; vertical-align:middle; margin-right:0.5rem;">
                HiddenClue
            </a>

            <!-- Botón menú hamburguesa (para vista móvil) -->
            <div class="menu-toggle" id="menu-toggle">☰</div>

            <!-- Menú de navegación -->
            <ul class="header-links" id="header-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                
                <!-- Si existe sesión, mostramos enlaces a reservar, perfil y cerrar sesión -->
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="reservar.php">Reservar</a></li>
                    <li><a href="perfil.php">👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                    <li><a href="includes/logout.php">Cerrar Sesión</a></li>
                <?php endif; ?>
            </ul>

        </div>
    </header>

    <!-- ===================== Sección principal ===================== -->
    <main>
        <div class="perfil-container">

            <!-- ===================== Sección de datos del perfil ===================== -->
            <div class="perfil-datos-section">

                <!-- Contenedor donde mostramos los datos actuales -->
                <div class="perfil-main-card" id="mostrar-datos">
                    <p class="perfil-nombre">Nombre: <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
                    <p class="perfil-email">Email: <?php echo htmlspecialchars($_SESSION['usuario_email']); ?></p>

                    <!-- La contraseña no se muestra por seguridad -->
                    <button type="button" id="btn-cambiar-datos">Cambiar datos</button>
                </div>

                <!-- Contenedor donde permitimos editar los datos -->
                <div class="perfil-main-card" id="editar-datos" style="display:none;">
                    <form id="Form_Cambio_De_Perfil" action="includes/procesar_cambio_de_datos_de_perfil.php" method="POST">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Cambia tu nombre de manera opcional">

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Cambia tu email de manera opcional">

                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Cambia tu password de manera opcional">
                        <small style="color:#e74c3c;">La contraseña se guardará de forma segura y no se puede ver.</small>

                        <button type="submit">Enviar</button>
                        <button type="button" id="btn-volver-datos">Volver</button>
                    </form>
                </div>
            </div>

            <!-- ===================== Sección de reservas ===================== -->
            <div class="perfil-reservas-section">
                <div class="perfil-main-card" id="mis-reservas">
                    <h2>Mis Reservas</h2>
                    <div class="reservas-container">
                        <?php
                        // =========================================================
                        // Incluimos la conexión a la base de datos
                        // =========================================================
                        require_once 'conexion/conexion.php';
                        
                        $usuario_id = $_SESSION['usuario_id'];
                        
                        // =========================================================
                        // Consulta para obtener todas las reservas del usuario
                        // =========================================================
                        $sql = "SELECT r.id_reserva, r.fecha_reserva, r.hora_reserva, r.num_personas, s.nombre as sala_nombre, 
                                    s.dificultad, s.duracion
                                FROM reservas r 
                                JOIN salas s ON r.id_sala = s.id_sala 
                                WHERE r.id_usuario = ? 
                                ORDER BY r.fecha_reserva DESC, r.hora_reserva DESC";
                        
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("i", $usuario_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        // =========================================================
                        // Mostramos las reservas si existen
                        // =========================================================
                        if ($result->num_rows > 0) {
                            while($reserva = $result->fetch_assoc()) {
                                echo '<div class="reserva-card">';
                                echo '<div class="reserva-info">';
                                echo '<h3>' . htmlspecialchars($reserva['sala_nombre']) . '</h3>';
                                echo '<p><strong>Fecha:</strong> ' . htmlspecialchars($reserva['fecha_reserva']) . '</p>';
                                echo '<p><strong>Hora:</strong> ' . htmlspecialchars($reserva['hora_reserva']) . '</p>';
                                echo '<p><strong>Personas:</strong> ' . htmlspecialchars($reserva['num_personas']) . '</p>';
                                echo '<p><strong>Dificultad:</strong> ' . htmlspecialchars($reserva['dificultad']) . '</p>';
                                echo '<p><strong>Duración:</strong> ' . htmlspecialchars($reserva['duracion']) . ' minutos</p>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="no-reservas">No tienes reservas realizadas.</p>';
                        }
                        
                        // Cerramos la consulta y la conexión
                        $stmt->close();
                        $conexion->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ===================== Pie de página ===================== -->
    <footer>
        <div class="footer-container">
            <p>&copy; 2025 HiddenClue - Todos los derechos reservados</p>

            <nav class="footer-links">
                <a href="index.php">Inicio</a>
                <a href="salas.php">Salas</a>
                <a href="reservar.php">Reservar</a>
                <a href="faq.php">FAQ</a>
                <a href="contacto.php">Contacto</a>
            </nav>

            <p class="footer-quote">🕵️‍♂️ El misterio te espera...</p>
        </div>
    </footer>

    <!-- ===================== Scripts ===================== -->
    <script src="js/perfil.js"></script>
</body>

</html>