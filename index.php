<?php
// =========================================================
// Archivo: index.php
// Descripción: Página principal de HiddenClue. Contiene el menú
// de navegación, la sección principal (hero), características
// destacadas y el pie de página. Muestra opciones dinámicas 
// según el estado de sesión del usuario.
// =========================================================
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Inicio</title>

    <!-- ===================== Estilos y fuentes ===================== -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" type="image/x-icon" href="img/Logo_Hidden_Clue.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

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

            <!-- Menú de navegación -->
            <ul class="header-links" id="header-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contacto.php">Contacto</a></li>

                <!-- Mostramos opciones según el estado de sesión -->
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

    <!-- ===================== Sección principal (Hero) ===================== -->
    <div class="hero">
        <!-- Contenedor donde se crean las partículas con JS -->
        <div class="particles" id="particles"></div>

        <!-- Contenido principal del hero -->
        <div class="hero-content">
            <h1 class="company-name">HiddenClue</h1>
            <p class="slogan">Descubre los misterios de Hidden Clue</p>
            <a href="salas.php" class="cta-button">Ver nuestras salas</a>
        </div>
    </div>

    <!-- ===================== Sección de características ===================== -->
    <section class="features">
        <div class="features-container">
            <!-- Tarjeta 1: Puzzles -->
            <div class="feature-card">
                <span class="feature-icon">🧩</span>
                <h3 class="feature-title">Puzzles Únicos</h3>
                <p class="feature-description">Desafíos diseñados para poner a prueba tu ingenio y trabajo en equipo</p>
            </div>
            <!-- Tarjeta 2: Tiempo de juego -->
            <div class="feature-card">
                <span class="feature-icon">⏱️</span>
                <h3 class="feature-title">30 Minutos - 1 hora</h3>
                <p class="feature-description">Media hora o 1 hora de adrenalina pura para resolver el misterio y escapar</p>
            </div>
            <!-- Tarjeta 3: Tamaño del grupo -->
            <div class="feature-card">
                <span class="feature-icon">👥</span>
                <h3 class="feature-title">Equipos de 2-8</h3>
                <p class="feature-description">Perfecto para amigos, familia o team building empresarial</p>
            </div>
        </div>
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
    <script src="js/index.js"></script>
</body>
</html>