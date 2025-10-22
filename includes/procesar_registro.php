<?php
session_start();
// Inicia la sesión para poder almacenar errores y datos temporales del formulario
// Esto permite mostrar mensajes de error y rellenar campos en caso de fallo
require_once "../conexion/conexion.php"; // Incluye la conexión a la base de datos
require_once "../conexion/Conexion_Gmail.php"; // Incluye las credenciales de Gmail para enviar correos de confirmación

// Recoger datos del formulario
$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';

// Array para almacenar errores
$errores = [];

// ===================== VALIDACIONES =====================
// Comprobar que todos los campos están completos
if (empty($nombre) || empty($email) || empty($password) || empty($confirm)) {
    $errores[] = "Todos los campos son obligatorios.";
}

// Validar que el correo tenga un formato válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El correo electrónico no es válido.";
}

// Validar que las contraseñas coincidan
if ($password !== $confirm) {
    $errores[] = "Las contraseñas no coinciden.";
}

// Validar longitud mínima de la contraseña
if (strlen($password) < 6) {
    $errores[] = "La contraseña debe tener al menos 6 caracteres.";
}

// Si existen errores, los guardamos en sesión y redirigimos de nuevo al formulario
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    // Guardamos los datos previos para rellenar el formulario
    $_SESSION['form_data'] = ['nombre' => $nombre, 'email' => $email];
    header("Location: ../registro.php");
    exit();
}

// ===================== COMPROBAR EMAIL EXISTENTE =====================
$stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Si el correo ya está registrado, enviamos error
    $_SESSION['errores'] = ["El correo ya está registrado."];
    $_SESSION['form_data'] = ['nombre' => $nombre];
    header("Location: ../registro.php");
    exit();
}
$stmt->close();

// ===================== INSERTAR USUARIO =====================
// Hashear la contraseña de forma segura
$hash = password_hash($password, PASSWORD_DEFAULT);

// Preparar la inserción en la base de datos
$stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $email, $hash);

if ($stmt->execute()) {
    // ===================== ENVÍO DE CORREO =====================
    // Incluir PHPMailer// Incluir PHPMailer manualmente
    require_once '../conexion/src/PHPMailer.php';
    require_once '../conexion/src/SMTP.php';
    require_once '../conexion/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $Username; 
        $mail->Password = $Password; 
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->SMTPOptions = array(
						'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true));

        $mail->Timeout=30;

        // Remitente
        $mail->setFrom($From, $FromName);

        // Destinatario
        $mail->addAddress($email, $nombre);

        // Contenido
        // Agregar logo embebido (usa la ruta de tu proyecto)
        $mail->addEmbeddedImage('../img/Logo_Hidden_Clue.png', 'logo_hiddenclue');

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = "Registro exitoso en HiddenClue";

        $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Registro Exitoso</title>
            </head>
            <body style="margin:0; padding:0; background-color:#111111; font-family: Arial, sans-serif;">

            <!-- Contenedor principal -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#111111; padding:40px 0;">
                <tr>
                <td align="center">

                    <!-- Caja central -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="background-color:#1c1c1c; border-radius:10px; overflow:hidden; color:#ffffff;">

                    <!-- Header con logo -->
                    <tr>
                        <td align="center" padding:20px;">
                        <img src="cid:logo_hiddenclue" alt="HiddenClue" style="max-width:120px; display:block;">
                        </td>
                    </tr>

                    <!-- Bienvenida -->
                    <tr>
                        <td style="padding:30px; text-align:center;">
                        <h2 style="margin:0; color:#00cc66;">Hola, ' . htmlspecialchars($nombre) . '</h2>
                        <p style="font-size:16px; color:#dddddd;">
                            Te has registrado correctamente en <b>HiddenClue</b>.
                        </p>
                        <p style="font-size:15px; color:#cccccc;">
                            Ahora puedes iniciar sesion y reservar nuestras salas de escape room.
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
                </td>
                </tr>
            </table>

            </body>
            </html>
            ';

        // Intentamos enviar el correo
        if ($mail->send()) {
            // Redirigimos a login con parámetro para indicar registro exitoso
            header("Location: ../login.php?registro=ok");
            exit();
        } else {
            $_SESSION['errores'] = ["Usuario creado, pero no se pudo enviar el correo de confirmación."];
            header("Location: ../registro.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['errores'] = ["Usuario creado, pero error al enviar correo: {$mail->ErrorInfo}"];
        header("Location: ../registro.php");
        exit();
    }
} else {
    // Error al insertar en la BD
    $_SESSION['errores'] = ["Error al registrar usuario: " . $conexion->error];
    header("Location: ../registro.php");
    exit();
}
?>