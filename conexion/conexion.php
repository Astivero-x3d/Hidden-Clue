<?php
// Carga las variables de configuración desde el archivo config.php
// Este archivo debe contener las variables de entorno con los datos de conexión (host, usuario, contraseña, base de datos)
require_once __DIR__ . '/../config/config.php';

// Se obtienen las variables de entorno definidas en config.php
// Estas variables guardan la información necesaria para conectar con la base de datos
$servidor = $_ENV['DB_HOST']; // Dirección del servidor de base de datos (por ejemplo: 'localhost' o una IP)
$usuario = $_ENV['DB_USER']; // Nombre de usuario para acceder a la base de datos
$contrasena = $_ENV['DB_PASSWORD']; // Contraseña del usuario de la base de datos
$base_de_datos = $_ENV['DB_NAME']; // Nombre de la base de datos que se va a utilizar

// Se crea una nueva conexión usando la extensión mysqli
// new mysqli(host, usuario, contraseña, base_de_datos)
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// Verifica si la conexión fue exitosa o hubo algún error
if ($conexion->connect_error) {
    // Si hay un error al conectar, se detiene el script y se muestra el mensaje de error
    die("Conexión fallida: " . $conexion->connect_error);
}

// Si llega a este punto, la conexión se realizó correctamente
// $conexion puede ser usada en cualquier parte del código para ejecutar consultas SQL
?>
