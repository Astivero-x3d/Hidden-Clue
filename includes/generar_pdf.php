<?php
// Iniciamos sesión para acceder a datos del usuario
session_start();

// Conexión a la base de datos
require_once '../conexion/conexion.php';

// Autoload de Composer para DomPDF
require_once '../vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

// ---- Verificamos que el usuario está logueado y que se ha pasado un ID de reserva ----
if (!isset($_SESSION['usuario_id']) || !isset($_GET['id_reserva'])) {
    header('Location: ../reservar.php'); // Si no, redirigimos
    exit();
}

$id_reserva = $_GET['id_reserva']; // ID de la reserva que queremos generar
$id_usuario = $_SESSION['usuario_id']; // ID del usuario que está logueado


try {
    // ---- Obtener información completa de la reserva con JOINs ----
    $stmt = $conexion->prepare('
        SELECT r.*, s.nombre as sala_nombre, s.descripcion as sala_descripcion, s.precio, 
                u.nombre as usuario_nombre, u.email as usuario_email
        FROM reservas r 
        INNER JOIN salas s ON r.id_sala = s.id_sala 
        INNER JOIN usuarios u ON r.id_usuario = u.id_usuario 
        WHERE r.id_reserva = ? AND r.id_usuario = ?
    ');
    $stmt->bind_param('ii', $id_reserva, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Reserva no encontrada"); // Error si no existe
    }

    $reserva = $result->fetch_assoc(); // Guardamos la info de la reserva
    $stmt->close();

    // ---- Configuración de DomPDF ----
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true); // Habilita HTML5
    $options->set('isRemoteEnabled', true); // Permite imágenes externas

    $dompdf = new Dompdf($options);

    // ---- Crear HTML del PDF ----
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            @page {
                margin: 0;
                padding: 0;
            }
            body {
                margin: 0;
                padding: 40px 60px;
                font-family: "Arial", sans-serif;
                background: #000000;
                color: #ffffff;
                box-sizing: border-box;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .logo {
                font-size: 36px;
                font-weight: bold;
                color: #27ae60;
                text-transform: uppercase;
                letter-spacing: 2px;
            }
            .subtitle {
                font-size: 14px;
                color: #cccccc;
                letter-spacing: 1px;
            }
            .title {
                text-align: center;
                font-size: 24px;
                text-transform: uppercase;
                color: #27ae60;
                margin: 30px 0 40px;
                border-top: 1px solid #27ae60;
                border-bottom: 1px solid #27ae60;
                padding: 10px 0;
            }
            .section {
                margin-bottom: 25px;
            }
            .info-row {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border-bottom: 1px solid #222;
            }
            .label {
                color: #27ae60;
                font-weight: bold;
                font-size: 14px;
                min-width: 180px;
            }
            .value {
                color: #ffffff;
                font-size: 14px;
                text-align: right;
                flex: 1;
            }
            .total {
                font-size: 20px;
                color: #27ae60;
                text-align: right;
                margin-top: 20px;
                border-top: 1px solid #27ae60;
                padding-top: 10px;
            }
            .code-box {
                border: 1px dashed #27ae60;
                text-align: center;
                padding: 25px;
                margin-top: 40px;
                border-radius: 8px;
            }
            .code {
                font-size: 22px;
                font-weight: bold;
                color: #27ae60;
                letter-spacing: 2px;
            }
            .instruction {
                color: #cccccc;
                font-style: italic;
                font-size: 13px;
                margin-top: 8px;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                color: #888;
                margin-top: 60px;
                border-top: 1px solid #222;
                padding-top: 15px;
            }
            .footer p {
                margin: 4px 0;
            }
        </style>
    </head>
    <body>

        <div class="header">
            <div class="logo">HiddenClue</div>
            <div class="subtitle">Escape Room Experience</div>
        </div>

        <div class="title">Comprobante de Reserva</div>

        <div class="section">
            <div class="info-row"><span class="label">Número de Reserva:</span><span class="value"> #' . $reserva['id_reserva'] . '</span></div>
            <div class="info-row"><span class="label">Fecha de Emisión:</span><span class="value"> ' . date('d/m/Y H:i') . '</span></div>
            <div class="info-row"><span class="label">Cliente:</span><span class="value"> ' . htmlspecialchars($reserva['usuario_nombre']) . '</span></div>
            <div class="info-row"><span class="label">Email:</span><span class="value"> ' . htmlspecialchars($reserva['usuario_email']) . '</span></div>
            <div class="info-row"><span class="label">Sala:</span><span class="value"> ' . htmlspecialchars($reserva['sala_nombre']) . '</span></div>
            <div class="info-row"><span class="label">Descripción:</span><span class="value"> ' . htmlspecialchars($reserva['sala_descripcion']) . '</span></div>
            <div class="info-row"><span class="label">Número de Personas:</span><span class="value"> ' . $reserva['num_personas'] . '</span></div>
            <div class="info-row"><span class="label">Fecha de Reserva:</span><span class="value"> ' . date('d/m/Y', strtotime($reserva['fecha_reserva'])) . '</span></div>
            <div class="info-row"><span class="label">Hora:</span><span class="value"> ' . $reserva['hora_reserva'] . '</span></div>
            <div class="info-row"><span class="label">Método de Pago:</span><span class="value"> ' . ucfirst($reserva['metodo_pago']) . '</span></div>';

    // Mensaje especial si existe
    if (!empty($reserva['mensaje'])) {
        $html .= '<div class="info-row"><span class="label">Mensaje Especial:</span><span class="value"> ' . htmlspecialchars($reserva['mensaje']) . '</span></div>';
    }

    $html .= '
        </div>

        <div class="total">Precio Total: ' . number_format($reserva['precio'], 2) . ' €</div>

        <div class="code-box">
            <div class="code">CÓDIGO DE RESERVA: HC-' . $reserva['id_reserva'] . '</div>
            <div class="instruction">Presenta este código al llegar al establecimiento</div>
        </div>

        <div class="footer">
            <p><strong>HiddenClue - Escape Room Experience</strong></p>
            <p>Email: ismaelast2005@gmail.com | iastillerogm@ismaelastillero.es</p>
            <p>¡Gracias por tu reserva! Te esperamos para vivir una experiencia única.</p>
        </div>

    </body>
    </html>';


    // ---- Generamos PDF con DomPDF ----

    // Cargar HTML
    $dompdf->loadHtml($html);

    // Tamaño A4 vertical
    $dompdf->setPaper('A4', 'portrait');

    // Renderizamos PDF
    $dompdf->render();

    // ---- Enviamos PDF al navegador para descarga ----
    $dompdf->stream("reserva_hiddenclue_{$id_reserva}.pdf", [
        "Attachment" => true
    ]);
} catch (Exception $e) {
    // Manejo de errores
    $_SESSION['error_pdf'] = "Error al generar el PDF: " . $e->getMessage();
    header('Location: ../reserva_exitosa.php');
    exit();
}
