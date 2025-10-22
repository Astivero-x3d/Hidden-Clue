<?php
// =========================================================
// Archivo: registro.php
// Descripción: Página de registro de usuario de HiddenClue.
// Permitimos crear una cuenta, mostrar errores y rellenar
// datos previamente ingresados en caso de error.
// =========================================================
session_start(); // Inicia la sesión para poder usar variables de sesión (guardar errores, datos, etc.)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Hacemos que la página se adapte a móviles, tablets y PC -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Registro de usuario</title>

    <!-- ===================== Estilos y recursos externos ===================== -->
    <link rel="stylesheet" href="css/registro.css">
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

            <!-- Botón menú hamburguesa (vista móvil) -->
            <div class="menu-toggle" id="menu-toggle">☰</div>

            <!-- Menú de navegación -->
            <ul class="header-links" id="header-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
                <li><a href="registro.php" class="btn-register">Registrarse</a></li>
            </ul>
        </div>
    </header>

    <!-- ===================== Sección principal: Formulario de registro ===================== -->
    <section class="registration-form">
        <h2>Crear Cuenta</h2>
        
        <?php
        // Mostramos errores si existen en la sesión
        if (isset($_SESSION['errores']) && !empty($_SESSION['errores'])) {
            echo '<div class="alert alert-error">';
            foreach ($_SESSION['errores'] as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
            
            // Limpiamos los errores después de mostrarlos
            unset($_SESSION['errores']);
        }
        
        // Obtenemos los datos previamente ingresados por el usuario
        // para rellenarlos en caso de error y no perderlos
        $nombre = '';
        $email = '';
        if (isset($_SESSION['form_data'])) {
            $nombre = $_SESSION['form_data']['nombre'] ?? '';
            $email = $_SESSION['form_data']['email'] ?? '';
            unset($_SESSION['form_data']);
        }
        ?>
        
        <form id="registerForm" action="includes/procesar_registro.php" method="POST">
            <!-- Campo de nombre completo -->
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <div class="error" id="nombreError"></div>
            </div>

            <!-- Campo de correo electrónico -->
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="error" id="emailError"></div>
            </div>

            <!-- Campo de contraseña -->
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <div class="error" id="passwordError"></div>
            </div>

            <!-- Campo de confirmación de contraseña -->
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <div class="error" id="confirmPasswordError"></div>
            </div>

            <!-- Botón de envío -->
            <button type="submit">Registrarse</button>
            
            <!-- Enlace para usuarios que ya tienen cuenta -->
            <div class="login-link">
                ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
            </div>
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
                <a href="contacto.php">Contacto</a>
                <a href="login.php">Iniciar sesión</a>
                <a href="registro.php">Registrarse</a>
            </nav>

            <p class="footer-quote">🕵️‍♂️ El misterio te espera...</p>
        </div>
    </footer>
    
    <!-- ===================== Scripts ===================== -->
    <script src="js/registro.js"></script>
</body>
</html>