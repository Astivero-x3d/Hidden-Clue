<?php
// Iniciamos la sesión para acceder a datos del usuario y de la reserva
session_start();

// Conexión a la base de datos principal
require_once '../conexion/conexion.php';

// ---- Verificación de sesión y datos de reserva ----
// Si el usuario no está logueado o no hay datos de reserva en la sesión, se redirige al formulario de reservar
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['datos_reserva'])) {
    header('Location: ../reservar.php');
    exit();
}

// Guardamos en variables locales los datos del usuario y la reserva
$id_usuario = $_SESSION['usuario_id'];
$datos_reserva = $_SESSION['datos_reserva'];

// Conexión para el envío de correos con Gmail
require_once '../conexion/Conexion_Gmail.php';

try {

    // ---- Obtener información del usuario ----
    // Se prepara la consulta para obtener el nombre y correo del usuario que hizo la reserva
    $stmt_usuario = $conexion->prepare('SELECT nombre, email FROM usuarios WHERE id_usuario = ?');
    $stmt_usuario->bind_param('i', $id_usuario);
    $stmt_usuario->execute();
    $result_usuario = $stmt_usuario->get_result();
    $usuario = $result_usuario->fetch_assoc(); // Guardamos los datos del usuario
    $stmt_usuario->close();

    // ---- Obtener información de la sala ----
    // Se obtiene el nombre y precio de la sala seleccionada para incluir en el correo y en la base de datos
    $stmt_sala = $conexion->prepare('SELECT nombre, precio FROM salas WHERE id_sala = ?');
    $stmt_sala->bind_param('i', $datos_reserva['id_sala']);
    $stmt_sala->execute();
    $result_sala = $stmt_sala->get_result();
    $sala = $result_sala->fetch_assoc(); // Guardamos los datos de la sala
    $stmt_sala->close();

    // ---- Insertar la reserva en la base de datos ----
    // Se prepara la consulta INSERT usando los datos guardados en sesión
    $stmt = $conexion->prepare('INSERT INTO reservas (id_usuario, id_sala, num_personas, fecha_reserva, hora_reserva, mensaje, metodo_pago) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('iiissss', 
        $id_usuario,
        $datos_reserva['id_sala'],
        $datos_reserva['num_personas'],
        $datos_reserva['fecha_reserva'],
        $datos_reserva['hora_reserva'],
        $datos_reserva['mensaje'],
        $datos_reserva['metodo_pago']
    );
    
    if ($stmt->execute()) {
        // ---- Reserva confirmada exitosamente ----
        $id_reserva = $conexion->insert_id; // Obtenemos el ID de la reserva recién creada
        $stmt->close();

        // ---- Envío de correo de confirmación ----
        // Incluimos la librería PHPMailer
        require_once '../conexion/src/PHPMailer.php';
        require_once '../conexion/src/SMTP.php';
        require_once '../conexion/src/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try{
            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $Username; // Usuario de Gmail definido en Conexion_Gmail.php
            $mail->Password = $Password; // Contraseña o App Password de Gmail
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Evitar problemas con certificados SSL autofirmados
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->Timeout=30;

        // Remitente
        $mail->setFrom($From, $FromName);

        // Destinatario
        $mail->addAddress($usuario['email'], $usuario['nombre']);

        // Contenido
        // Agregar logo embebido (usa la ruta de tu proyecto)
        $mail->addEmbeddedImage('../img/Logo_Hidden_Clue.png', 'logo_hiddenclue');

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = "Confirmacion de Reserva #" . $id_reserva . " - HiddenClue";

        // Formatear fecha y precio
        $fecha_formateada = date('d/m/Y', strtotime($datos_reserva['fecha_reserva']));
        $precio_total = number_format($sala['precio'], 2);

        // ---- Cuerpo del correo en HTML ----
        $mail->Body = '
                <!DOCTYPE html>
                <html>
                <head>
                <meta charset="UTF-8">
                <title>Confirmacion de Reserva</title>
                </head>
                <body style="margin:0; padding:0; background-color:#111111; font-family: Arial, sans-serif;">

                <!-- Contenedor Principal -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#111111; padding:40px 0;">
                    <tr>
                    <td align="center">

                        <!-- Caja central -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="background-color:#1c1c1c; border-radius:10px; overflow:hidden; color:#ffffff;">

                        <!-- Header con logo -->
                        <tr>
                            <td align="center" style="padding:20px;">
                            <img src="cid:logo_hiddenclue" alt="HiddenClue" style="max-width:120px; display:block;">
                            </td>
                        </tr>

                        <!-- Confirmación de reserva -->
                        <tr>
                            <td style="padding:30px; text-align:center;">
                            <h2 style="margin:0; color:#00cc66;">¡Reserva Confirmada!</h2>
                            <p style="font-size:16px; color:#dddddd;">
                                Hola, <b>' . htmlspecialchars($usuario['nombre']) . '</b>
                            </p>
                            <p style="font-size:15px; color:#cccccc;">
                                Tu reserva ha sido confirmada exitosamente. Aquí tienes los detalles:
                            </p>
                            </td>
                        </tr>

                        <!-- Detalles de la reserva -->
                        <tr>
                            <td style="padding:0 30px 30px 30px;">
                            <div style="background-color:#2a2a2a; padding:20px; border-radius:8px; text-align:left;">
                                <h3 style="color:#00cc66; margin-top:0;">Detalles de la Reserva</h3>
                                <p><strong>Número de reserva:</strong> #' . $id_reserva . '</p>
                                <p><strong>Sala:</strong> ' . htmlspecialchars($sala['nombre']) . '</p>
                                <p><strong>Número de personas:</strong> ' . $datos_reserva['num_personas'] . '</p>
                                <p><strong>Fecha:</strong> ' . $fecha_formateada . '</p>
                                <p><strong>Hora:</strong> ' . $datos_reserva['hora_reserva'] . '</p>
                                <p><strong>Método de pago:</strong> ' . ucfirst($datos_reserva['metodo_pago']) . '</p>
                                <p><strong>Precio total:</strong> ' . $precio_total . ' €</p>
                                ' . (!empty($datos_reserva['mensaje']) ? '<p><strong>Mensaje:</strong> ' . htmlspecialchars($datos_reserva['mensaje']) . '</p>' : '') . '
                            </div>
                            </td>
                        </tr>

                        <!-- Instrucciones -->
                        <tr>
                            <td style="padding:0 30px 30px 30px; text-align:center;">
                            <p style="font-size:14px; color:#cccccc;">
                                <strong>Importante:</strong> Por favor, llega 15 minutos antes de tu reserva.<br>
                                Presenta este correo o tu número de reserva (#' . $id_reserva . ') al llegar.
                            </p>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background-color:#000000; padding:15px; text-align:center; font-size:12px; color:#777;">
                            ' . date("Y") . ' HiddenClue. Todos los derechos reservados.
                            </td>
                        </tr>
                    </table>
                </body>
                </html>
        ';

        // Enviar correo
            if ($mail->send()) {
                // Si se envía correctamente, limpiamos los datos de la sesión de reserva
                unset($_SESSION['datos_reserva']);
                
                // Guardamos el ID de la reserva para mostrar en la página de éxito
                $_SESSION['reserva_exitosa'] = $id_reserva;
                header('Location: ../reserva_exitosa.php'); // Redirige a página de confirmación exitosa
                exit();
            } else {
                // Si falla el envío, lanzamos excepción
                throw new Exception("Reserva creada, pero no se pudo enviar el correo de confirmación.");
            }
        } catch (Exception $e) {
            // En caso de error en el correo, igualmente confirmamos la reserva
            unset($_SESSION['datos_reserva']);
            $_SESSION['reserva_exitosa'] = $id_reserva;
            $_SESSION['aviso_correo'] = "Reserva confirmada, pero no se pudo enviar el correo de confirmación.";
            header('Location: ../reserva_exitosa.php');
            exit();
        }
        
    } else {
        // Si falla la inserción en la base de datos
        $stmt->close();
        throw new Exception("Error al insertar la reserva");
    }
    
} catch (Exception $e) {
    // Captura errores generales y redirige con mensaje
    $_SESSION['error_reserva'] = "Hubo un error al confirmar tu reserva. Por favor, intenta nuevamente.";
    header('Location: ../reservar.php');
}

// Cerramos la conexión con la base de datos
$conexion->close();
?>