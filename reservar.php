<?php
// =========================================================
// Archivo: reservar.php
// Descripción: Página de reservas de HiddenClue. 
// Contiene un formulario para que los usuarios QUE TENGAN la sesion iniciada.
// Pueden rellenar los datos en este formulario, una vez ya rellenado 
// los datos, una vez rellenada se les llevara a la pagina de
// ResumenDeReserva.php
// =========================================================

// Iniciamos la sesión para poder acceder a los datos del usuario
session_start();

// Comprobamos si el usuario ha iniciado sesión; si no, lo redirigimos al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Mostramos errores de reserva si existen y luego los eliminamos de la sesión
if (isset($_SESSION['errores_reserva'])) {
    $errores = $_SESSION['errores_reserva'];
    unset($_SESSION['errores_reserva']);
}

// Restauramos los datos previos del formulario si existen y luego los eliminamos de la sesión
if (isset($_SESSION['datos_reserva'])) {
    $datos_previos = $_SESSION['datos_reserva'];
    unset($_SESSION['datos_reserva']);
}

// Inicializamos la variable mensaje para evitar errores si no se ha enviado nada
$mensaje = isset($datos_previos['mensaje']) ? $datos_previos['mensaje'] : '';

// Obtenemos la información de las salas desde la base de datos
require_once 'conexion/conexion.php';
$query = "SELECT id_sala, nombre, jugadores_min, jugadores_max FROM salas";
$result = $conexion->query($query);
$salas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $salas[$row['id_sala']] = $row;
    }
}
// Cerramos la conexión para liberar recursos
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Hacemos que la página se adapte a móviles, tablets y PC -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Reservar</title>

    <!-- ===================== Estilos y recursos externos ===================== -->
    <link rel="stylesheet" href="css/reservar.css">
    <link rel="icon" type="image/x-icon" href="img/Logo_Hidden_Clue.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Encabezado fijo con el logo y menú de navegación -->
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="img/Logo_Hidden_Clue.png" alt="Logo HiddenClue" style="width:80px; height:80px; vertical-align:middle; margin-right:0.5rem;">
                HiddenClue
            </a>

            <div class="menu-toggle" id="menu-toggle">☰</div>

            <ul class="header-links" id="header-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="reservar.php">Reservar</a></li>
                    <li><a href="perfil.php">👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a></li>
                    <li><a href="includes/logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
                    <li><a href="registro.php" class="btn-register">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <section class="reservar-form">
        <h2>Haz la reserva aqui abajo <span>↓</span></h2>

        <!-- Mostramos los errores si los hay -->
        <?php if (isset($errores) && !empty($errores)): ?>
            <div class="errores" style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #c62828;">
                <strong>Errores:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear la reserva -->
        <form action="includes/relleno_formulario_de_datos.php" method="POST" id="reservaForm">
            <!-- Selector de sala -->
            <label for="sala" class="form-label">Sala:</label>
            <select name="sala" id="form-select" class="form-select" required>
                <option value="">Selecciona una sala</option>
                <?php
                // Mostramos las salas con sus límites de jugadores
                foreach ($salas as $id_sala => $sala) {
                    echo '<option value="' . $id_sala . '" data-min="' . $sala['jugadores_min'] . '" data-max="' . $sala['jugadores_max'] . '">' . 
                        htmlspecialchars($sala['nombre']) . ' (' . $sala['jugadores_min'] . '-' . $sala['jugadores_max'] . ' personas)' . 
                        '</option>';
                }
                ?>
            </select>
            <small id="info-personas" style="display: block; margin-top: 5px; color: #666;">
                Selecciona una sala para ver los límites de personas
            </small>

            <!-- Número de personas -->
            <label for="num_personas" class="form-label">Número de personas:</label>
            <input type="number" name="num_personas" id="num_personas" class="form-input" min="1" required>
            <small id="error-personas" style="color: red; display: none;"></small>

            <!-- Fecha y hora de la reserva -->
            <label for="fech_reserva" class="form-label">Fecha de reserva:</label>
            <input type="date" name="fech_reserva" id="fech_reserva" class="form-input" required>

            <label for="hora_reserva" class="form-label" style="margin-top: 1em;">Hora de reserva:</label>
            <div id="hora-reserva-container">
                <input type="hidden" name="hora_reserva" id="hora_reserva" required>
                <div id="horas-visual-list" style="margin-top:1em;"></div>
                <small id="hora-seleccionada-text" style="display: block; margin-top: 10px; color: #666;">
                    Selecciona una sala y fecha
                </small>
            </div>

            <!-- Mensaje opcional del usuario -->
            <label for="mensaje" class="form-label">Mensaje (opcional):</label>
            <textarea id="mensaje" name="mensaje" maxlength="500" class="form-textarea" placeholder="Añade cualquier comentario adicional..."><?php echo htmlspecialchars($mensaje); ?></textarea>

            <!-- Método de pago -->
            <label for="met_de_pag" class="form-label">Método de pago:</label>
            <select name="met_de_pag" id="metodo-pago" class="form-select" required>
                <option value="">Selecciona método de pago</option>
                <option value="Paypal">Paypal</option>
                <option value="Bizum">Bizum</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Efectivo">Efectivo</option>
            </select>

            <!-- Botón para enviar la reserva -->
            <button type="submit" id="submitBtn">Enviar</button>
        </form>
    </section>

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
    
    <!-- Script JS para validar y mejorar la experiencia del formulario -->
    <script src="js/reservar.js"></script>
</body>
</html>