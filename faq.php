<?php 
// =========================================================
// Archivo: faq.php
// Descripción: Página de preguntas frecuentes de HiddenClue.
// Mostramos una lista de preguntas y respuestas sobre el registro,
// reservas, métodos de pago y soporte. Incluye control de sesión
// para mostrar opciones dinámicas en el menú.
// =========================================================
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Hacemos que la página se adapte a diferentes pantallas (móvil, tablet, PC) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - FAQ</title>

    <!-- ===================== Estilos y recursos externos ===================== -->
    <link rel="stylesheet" href="css/faq.css">
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

                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Mostramos opciones exclusivas si el usuario ha iniciado sesión -->
                    <li><a href="reservar.php">Reservar</a></li>
                    <li><a href="perfil.php">👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                    <li><a href="includes/logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <!-- Mostramos opciones de acceso si el usuario no ha iniciado sesión -->
                    <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
                    <li><a href="registro.php" class="btn-register">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- ===================== Sección principal: Preguntas frecuentes ===================== -->
    <main>
        <!-- Contenedor de preguntas -->
        <div class="contenedor-preguntas" id="contenedor-preguntas">
            <!-- Pregunta 1 -->
            <div class="lapregunta-card">
                <h2>1. ¿Cómo puedo registrarme en la plataforma?</h2>
                <div class="larespuesta-card">
                    <p>Para registrarte solo tienes que hacer clic en el botón “Registrarse” del menú principal, rellenar el formulario con tus datos y confirmar tu correo electrónico. Una vez hecho, podrás acceder a todas las funciones de la web.</p>
                </div>
            </div>

            <!-- Pregunta 2 -->
            <div class="lapregunta-card">
                <h2>2. ¿Es necesario crear una cuenta para reservar/comprar?</h2>
                <div class="larespuesta-card">
                    <p>Sí, es necesario tener una cuenta para poder completar una reserva o compra. Esto nos permite garantizar tu acceso, guardar tu historial y enviarte confirmaciones por correo.</p>
                </div>
            </div>

            <!-- Pregunta 3 -->
            <div class="lapregunta-card">
                <h2>3. ¿Qué métodos de pago están disponibles?</h2>
                <div class="larespuesta-card">
                    <p>Actualmente aceptamos pagos con tarjeta de crédito/débito, PayPal, transferencias bancarias y también aceptamos la paga en efectivo.</p>
                </div>
            </div>

            <!-- Pregunta 4 -->
            <div class="lapregunta-card">
                <h2>4. ¿Puedo modificar o cancelar una reserva/compra?</h2>
                <div class="larespuesta-card">
                    <p>Sí, puedes modificar o cancelar desde tu perfil en la sección “Mis reservas/mis compras”, siempre y cuando lo hagas con al menos 24 horas de antelación.</p>
                </div>
            </div>

            <!-- Pregunta 5 -->
            <div class="lapregunta-card">
                <h2>5. ¿Qué hago si tengo un problema con el acceso o la compra?</h2>
                <div class="larespuesta-card">
                    <p>Si tienes cualquier incidencia, puedes contactar con nuestro equipo desde la sección “Contacto” del menú. Intentaremos responderte lo antes posible para resolver tu problema.</p>
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
                <a href="faq.php">FAQ</a>
                <a href="reservar.php">Reservar</a>
                <a href="contacto.php">Contacto</a>

                <!-- Mostramos opciones de acceso si no hay sesión iniciada -->
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <a href="login.php">Iniciar sesión</a>
                    <a href="registro.php">Registrarse</a>
                <?php endif; ?>
            </nav>

            <p class="footer-quote">🕵️‍♂️ El misterio te espera...</p>
        </div>
    </footer>
    
    <!-- ===================== Scripts ===================== -->
    <script src="js/faq.js"></script>
</body>
</html>