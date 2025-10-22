<?php

// Iniciamos la sesión para poder guardar información como errores o datos del usuario
session_start();

// Incluimos la conexión a la base de datos
require_once "../conexion/conexion.php";

// Array para almacenar errores de validación
$errores = [];

// ====================== Verificación de envío por POST ======================
// Nos aseguramos de que los datos vienen del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recogemos y limpiamos los datos enviados por POST
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // ====================== Validaciones básicas ======================
    if (empty($email)) {
        $errores[] = "El campo correo electrónico es obligatorio.";
    }
    if (empty($password)) {
        $errores[] = "El campo contraseña es obligatorio.";
    }

    // ====================== Validación completa ======================
    if (empty($errores)) {
        // Preparamos la consulta para buscar el usuario por email
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email); // Asignamos el email al parámetro
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Si existe un usuario con ese email
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // ====================== Verificación de contraseña ======================
            // Comparamos la contraseña enviada con la que está guardada (hash)
            if (password_verify($password, $usuario['password'])) {
                // ====================== Inicio de sesión ======================
                // Guardamos los datos del usuario en sesión
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_password'] = $usuario['password'];

                // Redirigimos al usuario a la página principal
                header("Location: ../index.php");
                exit;
            } else {
                // Si la contraseña no coincide
                $errores[] = "El correo electrónico y/o la contraseña son incorrectos.";
            }
        } else {
            // Si no se encuentra el usuario
            $errores[] = "El correo electrónico y/o la contraseña son incorrectos.";
        }
    }
} else {
    // Si el acceso no es vía POST
    $errores[] = "Método no permitido.";
}

// ====================== Guardar errores y datos en sesión ======================
// Para poder mostrarlos en login.php
$_SESSION['errores'] = $errores;
$_SESSION['form_data'] = $_POST; // Guardamos los datos previos para rellenar el formulario

// Redirigimos de nuevo al formulario de login
header("Location: ../login.php");
exit;
?>
