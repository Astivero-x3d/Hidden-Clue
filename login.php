<?php
// =========================================================
// Archivo: login.php
// Descripción: Página de inicio de sesión de HiddenClue.
// Mostramos el formulario de login, gestionamos errores
// y rellenamos los campos con los datos previos si existen.
// =========================================================
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Hacemos que la página se adapte a diferentes pantallas (móvil, tablet, PC) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Inicio de sesión</title>

    <!-- ===================== Estilos y recursos externos ===================== -->
    <link rel="stylesheet" href="css/login.css">
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
                <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
                <li><a href="registro.php" class="btn-register">Registrarse</a></li>
            </ul>
        </div>
    </header>

    <!-- ===================== Sección principal: Formulario de login ===================== -->
    <section class="login-form">
        <h2>Iniciar sesión</h2>

        <?php
            // =========================================================
            // Mostramos errores del login si existen en sesión
            // =========================================================
            if (isset($_SESSION['errores']) && !empty($_SESSION['errores'])) {
                echo '<div class="alert alert-error">';
                foreach ($_SESSION['errores'] as $error) {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
                
                // Limpiamos los errores después de mostrarlos
                unset($_SESSION['errores']);
            }
            
            // =========================================================
            // Recuperamos los datos previos del formulario si existen
            // =========================================================
            $password = '';
            $email = '';
            if (isset($_SESSION['form_data'])) {
                $password = $_SESSION['form_data']['password'] ?? '';
                $email = $_SESSION['form_data']['email'] ?? '';
                // Limpiamos los datos de sesión
                unset($_SESSION['form_data']);
            }
        ?>

        <!-- Formulario principal de inicio de sesión -->
        <form id="loginForm" action="includes/procesar_login.php" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                <div class="error" id="passwordError"></div>
            </div>

            <button type="submit">Iniciar sesión</button>

            <div class="registro-link">
                ¿No tienes una cuenta? <a href="registro.php">Crea tu cuenta aquí</a>
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
    <script src="js/login.js"></script>
</body>
</html>