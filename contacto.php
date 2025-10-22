<?php 
// =========================================================
// Archivo: contacto.php
// Descripción: Página de contacto de HiddenClue. Contiene un 
// formulario para que los usuarios envíen consultas o sugerencias.
// Se gestionan las variables de sesión para mantener los datos 
// ingresados y mostrar mensajes de éxito.
// =========================================================
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Contacto</title>

    <!-- ===================== Estilos y recursos externos ===================== -->
    <link rel="stylesheet" href="css/contacto.css">
    <link rel="icon" type="image/x-icon" href="img/Logo_Hidden_Clue.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- ===================== Fondo de partículas ===================== -->
    <div id="particles"></div>
    
    <!-- ===================== Encabezado: Logo y navegación ===================== -->
    <header>
        <div class="header-container">
            <!-- Logo con enlace al inicio -->
            <a href="index.php" class="logo">
                <img src="img/Logo_Hidden_Clue.png" alt="Logo HiddenClue" style="width:80px; height:80px; vertical-align:middle; margin-right:0.5rem;">
                HiddenClue
            </a>

            <!-- Botón menú hamburguesa (para móviles) -->
            <div class="menu-toggle" id="menu-toggle">☰</div>
        <?php
            // =====================================================
            // Inicializamos de variables para el formulario
            // =====================================================
            // Si existen datos previos guardados en sesión (por error de validación),
            // se recuperan para que el usuario no pierda lo que había escrito.
            $nombre = $_SESSION['nombre'] ?? '';
            $email = $_SESSION['email'] ?? '';
            $asunto = $_SESSION['asunto'] ?? '';
            $mensaje = $_SESSION['mensaje'] ?? '';

            // Una vez recuperados, se eliminan para evitar persistencia innecesaria
            unset($_SESSION['nombre'], $_SESSION['email'], $_SESSION['asunto'], $_SESSION['mensaje']);
        ?>

            <!-- Menú de navegación -->
            <ul class="header-links" id="header-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contacto.php">Contacto</a></li>

                <!-- Mostramos opciones dinámicas según la sesión -->
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Usuario con sesión iniciada -->
                    <li><a href="reservar.php">Reservar</a></li>
                    <li><a href="perfil.php">👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                    <li><a href="includes/logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <!-- Usuario sin sesión iniciada -->
                    <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
                    <li><a href="registro.php" class="btn-register">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- ===================== Formulario de contacto ===================== -->
    <section class="contact-form">
        <h2>Contacto</h2>

        <?php
            // =====================================================
            // Mostramos mensaje de éxito tras enviar el formulario
            // =====================================================
            if (isset($_SESSION['exito'])) {
                echo '<div class="alert alert-success">';
                echo '<p>' . htmlspecialchars($_SESSION['exito']) . '</p>';
                echo '</div>';
                
                // Limpiamos el mensaje de éxito después de mostrarlo
                unset($_SESSION['exito']);
            }
            ?>


        <!-- Formulario principal -->
        <form id="contactoForm" action="includes/procesar_contacto.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <div class="error" id="emailError"></div>

            <label for="asunto">Asunto:</label>
            <input type="text" id="asunto" name="asunto" value="<?php echo htmlspecialchars($asunto); ?>" required>

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" maxlength="500" required><?php echo htmlspecialchars($mensaje); ?></textarea>

            <button type="submit">Enviar</button>
        </form>
    </section>

    <!-- ===================== Pie de página ===================== -->
    <footer>
        <div class="footer-container">
            <p>&copy; 2025 HiddenClue - Todos los derechos reservados</p>
            
            <nav class="footer-links">
                <a href="index.php">Inicio</a>
                <a href="salas.php">Salas</a>
                <a href="faq.php">FAQ</a>
                <a href="reservar.php">Reservar</a>
                <a href="contacto.php">Contacto</a>

                <!-- Si el usuario no ha iniciado sesión, mostramos las opciones de acceso -->
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <a href="login.php">Iniciar sesión</a>
                    <a href="registro.php">Registrarse</a>
                <?php endif; ?>
            </nav>

            <p class="footer-quote">🕵️‍♂️ El misterio te espera...</p>
        </div>
    </footer>
    
    <!-- ===================== Scripts ===================== -->
    <script src="js/contacto.js"></script>
</body>
</html>