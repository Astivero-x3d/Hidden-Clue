<?php
// Incluimos el archivo de configuración principal, que carga las variables del archivo .env
require_once __DIR__ . '/../config/config.php';

// Extraemos las credenciales de Gmail desde las variables de entorno (.env)
// Esto nos permite mantener la seguridad y no dejar las claves escritas directamente en el código

// Guardamos el nombre de usuario del correo Gmail (la dirección desde la que enviamos los mensajes)
$Username = $_ENV['GMAIL_USERNAME'];

// Guardamos la contraseña o clave de aplicación del correo Gmail
// ⚠️ Importante: nunca debemos escribir esta clave directamente en el código, solo en el archivo .env
$Password = $_ENV['GMAIL_PASSWORD'];

// Guardamos el correo electrónico que aparecerá como remitente
$From = $_ENV['GMAIL_FROM'];

// Guardamos el nombre que aparecerá como remitente en los correos enviados
$FromName = $_ENV['GMAIL_FROM_NAME'];
?>