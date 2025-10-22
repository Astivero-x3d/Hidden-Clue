<?php

// Iniciamos la sesión para poder usar variables de sesión
session_start();

// Cargamos la conexión a la base de datos
require_once '../conexion/conexion.php';

// Cargamos variables de Gmail para PHPMailer
require_once '../conexion/Conexion_Gmail.php';

// Cargamos la librería PHPMailer
require_once '../conexion/src/PHPMailer.php';
require_once '../conexion/src/SMTP.php';
require_once '../conexion/src/Exception.php';

// Inicializamos array para almacenar errores de validación
$errores = [];

// Recogemos y limpiamos los datos enviados por POST (formulario de contacto)
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$asunto = trim($_POST['asunto'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// ====================== Validación básica ======================
// Verificamos que cada campo obligatorio esté completo
if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El correo electrónico es obligatorio y debe ser válido.';
}
if ($asunto === '') {
    $errores[] = 'El asunto es obligatorio.';
}
if ($mensaje === '') {
    $errores[] = 'El mensaje es obligatorio.';
}

// Si hay errores, los guardamos en sesión para mostrarlos en contacto.php
// También guardamos los valores del formulario para no perderlos
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['email'] = $email;
    $_SESSION['asunto'] = $asunto;
    $_SESSION['mensaje'] = $mensaje;
    header('Location: ../contacto.php');
    exit;
}

// ====================== Insertar en base de datos ======================
try {
    // Preparamos consulta para insertar el mensaje en la tabla "contacto"
    $stmt = $conexion->prepare('INSERT INTO contacto (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $nombre, $email, $asunto, $mensaje);
    $stmt->execute();
    $stmt->close();

    // Indicamos éxito para mostrar mensaje en contacto.php
    $_SESSION['exito'] = '¡Tu mensaje ha sido enviado correctamente!';

    // Limpiamos los datos previos guardados en sesión
    unset($_SESSION['nombre'], $_SESSION['email'], $_SESSION['asunto'], $_SESSION['mensaje']);

    // ====================== Enviar correo a administradores ======================
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP(); // Usamos SMTP
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $Username; // Usuario Gmail

    $mail->Password = $Password; // Contraseña o app password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom($From, $FromName);

    // Destinatarios: administradores

    $mail->addAddress('ismaelast2005@gmail.com');
    $mail->addAddress('iastillerogm@ismaelastillero.es');

    $mail->isHTML(true); // Formato HTML
    $mail->Subject = 'Nuevo mensaje de contacto: ';

    // Cuerpo del correo con formato HTML
    $mail->Body = '<div style="background-color:#111; color:#fff; border-radius:10px; padding:20px; font-family:Arial, sans-serif; max-width:500px; margin:auto; border:2px solid #0f0;">'
                  . '<h2 style="color:#0f0; text-align:center;">Nuevo mensaje de contacto</h2>'
                  . '<div style="background:#222; padding:15px; border-radius:8px; margin-bottom:10px;">'
                  . '<b style="color:#0f0;">Nombre:</b> <span style="color:#fff;">' . htmlspecialchars($nombre) . '</span><br>'
                  . '<b style="color:#0f0;">Email:</b> <span style="color:#fff;">' . htmlspecialchars($email) . '</span><br>'
                  . '<b style="color:#0f0;">Asunto:</b> <span style="color:#fff;">' . htmlspecialchars($asunto) . '</span><br>'
                  . '<b style="color:#0f0;">Mensaje:</b><br>'
                  . '<div style="color:#fff; background:#111; padding:10px; border-radius:6px;">' . nl2br(htmlspecialchars($mensaje)) . '</div>'
                  . '</div>'
                  . '</div>';
    $mail->send();

    // ====================== Enviar correo al usuario (resumen) ======================
    $mailUser = new PHPMailer\PHPMailer\PHPMailer(true);
    $mailUser->isSMTP();
    $mailUser->Host = 'smtp.gmail.com';
    $mailUser->SMTPAuth = true;
    $mailUser->Username = $Username;
    $mailUser->Password = $Password;
    $mailUser->SMTPSecure = 'ssl';
    $mailUser->Port = 465;
    $mailUser->setFrom($From, $FromName);
    $mailUser->addAddress($email);
    $mailUser->isHTML(true);
    $mailUser->Subject = 'Resumen de tu mensaje en HiddenClue: ' . htmlspecialchars($asunto);
    $mailUser->Body = '<h2>Gracias por contactarnos</h2>' .
                      '<div style="background-color:#111; color:#fff; border-radius:10px; padding:20px; font-family:Arial, sans-serif; max-width:500px; margin:auto; border:2px solid #0f0;">'
                      . '<h2 style="color:#0f0; text-align:center;">¡Gracias por contactarnos!</h2>'
                      . '<p style="color:#fff; text-align:center;">Este es el resumen de tu mensaje enviado:</p>'
                      . '<div style="background:#222; padding:15px; border-radius:8px; margin-bottom:10px;">'
                      . '<b style="color:#0f0;">Nombre:</b> <span style="color:#fff;">' . htmlspecialchars($nombre) . '</span><br>'
                      . '<b style="color:#0f0;">Email:</b> <span style="color:#fff;">' . htmlspecialchars($email) . '</span><br>'
                      . '<b style="color:#0f0;">Asunto:</b> <span style="color:#fff;">' . htmlspecialchars($asunto) . '</span><br>'
                      . '<b style="color:#0f0;">Mensaje:</b><br>'
                      . '<div style="color:#fff; background:#111; padding:10px; border-radius:6px;">' . nl2br(htmlspecialchars($mensaje)) . '</div>'
                      . '</div>'
                      . '<p style="color:#0f0; text-align:center; margin-top:20px;">Nos pondremos en contacto contigo lo antes posible.</p>'
                      . '</div>';
    $mailUser->send();

} catch (Exception $e) {
    // Si hay algún error (DB o envío de correo), lo guardamos en sesión
    $_SESSION['errores'] = ['Error al guardar el mensaje o enviar correo: ' . $e->getMessage()];
}

// Redirigimos de vuelta a contacto.php
header('Location: ../contacto.php');
exit;

?>