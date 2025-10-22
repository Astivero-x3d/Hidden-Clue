<?php
// =========================================================
// Archivo: ResumenDeReserva.php
// Descripción: Resumen de la reserva en reservar.php 
// Muestra los datos que ha rellenado el usuario en el formulario
// =========================================================

// Iniciamos la sesión para poder acceder a los datos del usuario y de la reserva
session_start();
require_once 'conexion/conexion.php';

// Verificamos que el usuario está logueado y que existen los datos de la reserva
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['datos_reserva'])) {
    // Si no, lo redirigimos al formulario de reservas
    header('Location: reservar.php');
    exit();
}

// Guardamos los datos del usuario y de la reserva en variables locales
$id_usuario = $_SESSION['usuario_id'];
$datos_reserva = $_SESSION['datos_reserva'];

// Obtenemos la información de la sala seleccionada
$stmt = $conexion->prepare('SELECT nombre, descripcion, precio, jugadores_min, jugadores_max FROM salas WHERE id_sala = ?');
$stmt->bind_param('i', $datos_reserva['id_sala']);
$stmt->execute();
$result = $stmt->get_result();
$sala = $result->fetch_assoc();
$stmt->close();

// Calculamos el precio total de la reserva
// Por ahora usamos el precio de la sala tal cual, pero podemos ajustarlo según el número de personas o promociones
$precio_total = $sala['precio'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Resumen de Reserva</title>
    <link rel="stylesheet" href="css/reservar.css">
    <link rel="icon" type="image/x-icon" href="img/Logo_Hidden_Clue.png">
</head>
<body>
    <!-- Encabezado con logo -->
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="img/Logo_Hidden_Clue.png" alt="Logo HiddenClue" style="width:80px; height:80px; vertical-align:middle; margin-right:0.5rem;">
                HiddenClue
            </a>
        </div>
    </header>

    <!-- Sección principal con el resumen de la reserva -->
    <section class="reservar-form">
        <h2>Resumen de tu Reserva</h2>
        
        <div class="resumen-reserva">
            <h3>Detalles de la Reserva</h3>
            
            <div class="detalle-reserva">
                <p><strong>Sala:</strong> <?php echo htmlspecialchars($sala['nombre']); ?></p>
                <p><strong>Número de personas:</strong> <?php echo $datos_reserva['num_personas']; ?> (mín: <?php echo $sala['jugadores_min']; ?>, máx: <?php echo $sala['jugadores_max']; ?>)</p>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($datos_reserva['fecha_reserva'])); ?></p>
                <p><strong>Hora:</strong> <?php echo $datos_reserva['hora_reserva']; ?></p>
                <p><strong>Mensaje:</strong> <?php echo !empty($datos_reserva['mensaje']) ? htmlspecialchars($datos_reserva['mensaje']) : 'Sin mensaje'; ?></p>
                <p><strong>Método de pago:</strong> <?php echo ucfirst($datos_reserva['metodo_pago']); ?></p>
                <p><strong>Precio total:</strong> <?php echo number_format($precio_total, 2); ?> €</p>
            </div>

            <!-- Botones para confirmar o modificar la reserva -->
            <form action="includes/confirmar_reserva.php" method="POST" style="margin-top: 2em;">
                <button type="submit" class="btn-confirmar">Confirmar Reserva</button>
                <a href="reservar.php" class="btn-cancelar">Modificar Reserva</a>
            </form>
        </div>
    </section>

    <!-- Pie de página -->
    <footer>
        <div class="footer-container">
            <p>&copy; 2025 HiddenClue - Todos los derechos reservados</p>
        </div>
    </footer>
</body>
</html>