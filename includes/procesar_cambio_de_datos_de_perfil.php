<?php
// Iniciamos la sesión para poder acceder a los datos del usuario logueado
session_start();

// Incluimos la conexión a la base de datos
require_once '../conexion/conexion.php'; // Ajusta la ruta si es necesario

// ===========================
// 1. Comprobamos si hay sesión activa
// ===========================
// Si no hay sesión de usuario, respondemos con un error 401 (No autorizado)
// Esto protege que solo usuarios autenticados puedan cambiar sus datos
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // Código HTTP de "No autorizado"
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Guardamos el id del usuario de la sesión
$id = $_SESSION['usuario_id'];

// ===========================
// 2. Recogemos los datos enviados por POST desde el formulario
// ===========================
// Se usan trim() para eliminar espacios extra
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// ===========================
// 3. Preparamos los campos a actualizar
// ===========================
// Array que contendrá los campos que realmente se van a actualizar
$campos = [];
$params = [];


// Si el usuario ingresó un nombre, agregamos al array de campos
if ($nombre !== '') {
    $campos[] = 'nombre = ?';
    $params[] = $nombre;
}

// Si el usuario ingresó un email, agregamos al array de campos
if ($email !== '') {
    $campos[] = 'email = ?';
    $params[] = $email;
}

// Si el usuario ingresó una contraseña, la hasheamos y agregamos al array
if ($password !== '') {
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash seguro
    $campos[] = 'password = ?';
    $params[] = $password_hash;
}

// ===========================
// 4. Actualizamos la base de datos si hay campos que modificar
// ===========================
if (count($campos) > 0) {
    // Construimos la consulta SQL dinámicamente según los campos a actualizar
    $sql = 'UPDATE usuarios SET ' . implode(', ', $campos) . ' WHERE id_usuario = ?';

    // Agregamos el id del usuario al final de los parámetros
    $params[] = $id;

    // Preparamos la sentencia
    $stmt = $conexion->prepare($sql);

    // Ejecutamos la sentencia con los parámetros
    // IMPORTANTE: aquí se debería usar bind_param para mayor seguridad
    if ($stmt && $stmt->execute($params)) {

        // ===========================
        // 5. Actualizamos datos de sesión
        // ===========================
        // Si se cambió el nombre o email, actualizamos la sesión
        if ($nombre !== '') {
            $_SESSION['usuario_nombre'] = $nombre;
        }
        if ($email !== '') {
            $_SESSION['usuario_email'] = $email;
        }

    // ===========================
    // 6. Redirigimos al perfil después de actualizar
    // ===========================
    header('Location: ../perfil.php'); // Volvemos a perfil.php
    exit();
    } else {
        // En caso de error en la actualización
        echo json_encode(['error' => 'Error al actualizar los datos']);
    }
} else {
    // Si no se enviaron datos para actualizar
    echo json_encode(['error' => 'No se enviaron datos para actualizar']);
}
?>
