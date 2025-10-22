/* 
=========================================================
Archivo: salas.php
Descripción: Resumen de las salas en salas.php 
Muestra todas las salas que hay para el escape room,
pudes filtrarlo por dificultad, ordenarlo como tu quieras
y buscar el nombre de la sala 
========================================================= 
*/
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Salas</title>

    <!-- Enlace al CSS -->
    <link rel="stylesheet" href="css/salas.css">

    <!-- Icono de la página -->
    <link rel="icon" type="image/x-icon" href="img/Logo_Hidden_Clue.png">

    <!-- Enlace al JavaScript de la página -->
    <script src="js/salas.js"></script>

    <!-- Fuente de Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Encabezado fijo con logo y menú de navegación -->
    <header>
        <div class="header-container">
            <!-- Logo + enlace al inicio -->
            <a href="index.php" class="logo">
                <img src="img/Logo_Hidden_Clue.png" alt="Logo HiddenClue" style="width:80px; height:80px; vertical-align:middle; margin-right:0.5rem;">
                HiddenClue
            </a>

            <!-- Botón menú hamburguesa -->
            <div class="menu-toggle" id="menu-toggle">☰</div>

            <!-- Menú de navegación dinámico según sesión -->
            <?php session_start(); ?>
            <ul class="header-links" id="header-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Si el usuario está logueado, mostrar enlaces adicionales -->
                    <li><a href="reservar.php">Reservar</a></li>
                    <li><a href="perfil.php">👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                    <li><a href="includes/logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <!-- Si no está logueado, mostrar opciones de login/registro -->
                    <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
                    <li><a href="registro.php" class="btn-register">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- Contenido principal de la página -->
    <main>
        <!-- Sección de filtros -->
        <section class="salas-filtros">
            <form id="form-filtros" autocomplete="off">
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar sala por nombre..." />
                <select name="dificultad" id="dificultad">
                    <option value="">Todas las dificultades</option>
                    <option value="Fácil">Fácil</option>
                    <option value="Media">Media</option>
                    <option value="Díficil">Difícil</option>
                </select>
                <select name="orden" id="orden">
                    <option value="nombre">Nombre</option>
                    <option value="precio">Precio</option>
                    <option value="duracion">Duración</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>
        </section>

        <!-- Sección donde se mostrarán las salas filtradas -->
        <section class="salas-listado" id="salas-listado">
            <!-- El contenido se cargará dinámicamente con JS -->
        </section>

        <!-- Contenedor para la paginación de las salas -->
        <div class="paginacion" id="paginacion">
            <!-- Los botones de paginación se generarán dinámicamente con JS -->
        </div>
    </main>

    <!-- Píe de página -->
    <footer>
        <div class="footer-container">
            <p>&copy; 2025 HiddenClue - Todos los derechos reservados</p>
            
            <nav class="footer-links">
                <a href="index.php">Inicio</a>
                <a href="salas.php">Salas</a>
                <a href="faq.php">FAQ</a>
                <a href="reservar.php">Reservar</a>
                <a href="contacto.php">Contacto</a>
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <a href="login.php">Iniciar sesión</a>
                    <a href="registro.php">Registrarse</a>
                <?php endif; ?>
            </nav>

            <p class="footer-quote">🕵️‍♂️ El misterio te espera...</p>
        </div>
    </footer>
</body>
</html>