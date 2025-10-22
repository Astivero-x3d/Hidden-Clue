<?php
// Requerimos el autoload de Composer para poder usar las librerías instaladas
require_once __DIR__ . '/../vendor/autoload.php';

// Importamos la clase Dotenv para gestionar las variables de entorno
use Dotenv\Dotenv;

// Cargamos las variables del archivo .env ubicado en la raíz del proyecto
// Esto nos permite guardar información sensible (como contraseñas, correos o claves)
// sin tener que escribirla directamente en el código fuente
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

// Activamos la carga de las variables de entorno
// A partir de aquí, podemos acceder a ellas con $_ENV['NOMBRE_VARIABLE']
$dotenv->load();
