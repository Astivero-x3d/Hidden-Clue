<?php
// =========================================================
// Archivo: reserva:exitosa.php
// Descripción: Confirmación de reserva de Hidden Clue.
// Le damos la confirmación al cliente de que la reserva
// ha sido existosa y pueda descargar el ticket del pdf
// =========================================================

// Iniciamos la sesión para poder acceder a los datos de la reserva
session_start();

// Comprobamos si existe la variable de sesión 'reserva_exitosa'
// Esta variable se establece en reservar.php al confirmar la reserva
// Si no existe, significa que alguien intentó acceder directamente a esta página
// por lo que redirigimos a la página de reservas
if (!isset($_SESSION['reserva_exitosa'])) {
    header('Location: reservar.php'); // Redirige al formulario de reservas
    exit(); // Detiene la ejecución del script
}

// Guardamos el ID de la reserva confirmada en una variable para mostrarla en la página
$id_reserva = $_SESSION['reserva_exitosa'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Hacemos que la página se adapte a móviles, tablets y PC -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiddenClue - Reserva Confirmada</title>

    <!-- Enlazamos la hoja de estilos y el ícono del sitio -->
    <link rel="stylesheet" href="css/reservar.css">
    <link rel="icon" type="image/x-icon" href="img/Logo_Hidden_Clue.png">
</head>
<body>
    <header>
        <!-- Mostramos el logo y el nombre del sitio -->
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="img/Logo_Hidden_Clue.png" alt="Logo HiddenClue" style="width:80px; height:80px; vertical-align:middle; margin-right:0.5rem;">
                HiddenClue
            </a>
        </div>
    </header>

    <section class="reservar-form">
        <!-- Mostramos el mensaje de confirmación de reserva -->
        <div style="text-align: center; padding: 2em;">
            <h2 style="color: green;">¡Reserva Confirmada!</h2>
            <p>Tu reserva ha sido procesada exitosamente.</p>
            <!-- Mostramos el número de reserva dinámicamente -->
            <p><strong>Número de reserva:</strong> #<?php echo $id_reserva; ?></p>
            <p>Te hemos enviado un email de confirmación con todos los detalles.</p>

            <!-- Mostramos un botón para descargar el comprobante de la reserva en formato PDF -->
            <div style="margin: 2em 0; padding: 1em; background: #f9f9f9; border-radius: 8px; display: inline-block;">
                <h3 style="margin-bottom: 1em;">Descargar Comprobante</h3>
                <a href="includes/generar_pdf.php?id_reserva=<?php echo $id_reserva; ?>" class="btn-confirmar" style="text-decoration: none;">
                    📄 Descargar PDF de la Reserva
                </a>
            </div>
            
            <!-- Añadimos botones para volver al inicio o consultar las reservas del usuario -->
            <div style="margin-top: 2em;">
                <a href="index.php" class="btn-confirmar">Volver al Inicio</a>
                <a href="perfil.php" class="btn-cancelar">Ver Mis Reservas</a>
            </div>
        </div>
    </section>

    <footer>
        <!-- Mostramos el pie de página con los derechos reservados -->
        <div class="footer-container">
            <p>&copy; 2025 HiddenClue - Todos los derechos reservados</p>
        </div>
    </footer>
</body>
</html>
<?php
// Eliminamos la variable de sesión 'reserva_exitosa' para evitar que se muestre nuevamente al recargar la página
unset($_SESSION['reserva_exitosa']);
?>