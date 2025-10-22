<?php
// Iniciamos la sesión para poder acceder/guardar datos en $_SESSION
session_start();

// Conexión a la base de datos
require_once '../conexion/conexion.php';

// Verificamos que el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

// Verificamos que se envió el formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../reservar.php');
    exit();
}

// Recogemos y validamos datos

// ID de usuario desde la sesión (quien realiza la reserva)
$id_usuario = $_SESSION['usuario_id'];

// Datos enviados por POST (convertimos a int donde corresponda y limpiamos)
$id_sala = intval($_POST['sala']);
$num_personas = intval($_POST['num_personas']);

// formato esperado: YYYY-MM-DD
$fecha_reserva = $_POST['fech_reserva'];

// formato esperado: HH:MM
$hora_reserva = $_POST['hora_reserva'];

// Mensaje opcional
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
$metodo_pago = $_POST['met_de_pag'];

// Validaciones básicas
$errores = [];

// Sala válida (id positivo)
if ($id_sala <= 0) {
    $errores[] = "Sala no válida";
}

if ($num_personas <= 0) {
    $errores[] = "Número de personas no válido";
}

// Validar formato de fecha YYYY-MM-DD con regex
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_reserva)) {
    $errores[] = "Formato de fecha inválido";
}

// Validamos formato de hora HH:MM con regex
if (!preg_match('/^\d{2}:\d{2}$/', $hora_reserva)) {
    $errores[] = "Formato de hora inválido";
}

// Método de pago obligatorio
if (empty($metodo_pago)) {
    $errores[] = "Selecciona un método de pago";
}

// Verificamos que la sala existe y validar límites de personas
if (empty($errores)) {
    $stmt = $conexion->prepare('SELECT jugadores_min, jugadores_max FROM salas WHERE id_sala = ?');
    $stmt->bind_param('i', $id_sala);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Si no existe la sala, error
    if ($result->num_rows === 0) {
        $errores[] = "La sala seleccionada no existe";
    } else {
        // Si existe, comprobamos límites de personas
        $sala = $result->fetch_assoc();
        if ($num_personas < $sala['jugadores_min'] || $num_personas > $sala['jugadores_max']) {
            $errores[] = "El número de personas debe estar entre " . $sala['jugadores_min'] . " y " . $sala['jugadores_max'];
        }
    }
    $stmt->close();
}

// Verificamos que la hora esté disponible
if (empty($errores)) {
    $stmt = $conexion->prepare('SELECT id_reserva FROM reservas WHERE id_sala = ? AND fecha_reserva = ? AND hora_reserva = ?');
    $stmt->bind_param('iss', $id_sala, $fecha_reserva, $hora_reserva);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Si ya hay alguna fila, la hora está ocupada
    if ($result->num_rows > 0) {
        $errores[] = "La hora seleccionada ya está ocupada";
    }
    $stmt->close();
}

/*
    --------------- Validación: no permitir reservas para el mismo día ---------------
    (Se añadió esta validación explícita: evita reservas 'hoy')
*/
$ahora = new DateTime();
$fecha_reserva_obj = new DateTime($fecha_reserva);

if ($fecha_reserva_obj->format('Y-m-d') === $ahora->format('Y-m-d')) {
    $errores[] = "No se pueden hacer reservas para el mismo día. Por favor, selecciona una fecha futura.";
}

/*
    --------------- Si hay errores -> guardar en sesión y redirigir a reservar.php ---------------
    Guardamos $_SESSION['errores_reserva'] y los datos del formulario en $_SESSION['datos_reserva']
    para que el formulario pueda mostrar errores y rellenar los campos.
*/
if (!empty($errores)) {
    $_SESSION['errores_reserva'] = $errores;
    $_SESSION['datos_reserva'] = $_POST; // guardamos todo lo recibido para reutilizar en el formulario
    header('Location: ../reservar.php');
    exit();
}

/*
    --------------- Validación extra: verificar que la hora/fecha no haya pasado ---------------
    (Comprobaciones adicionales del servidor para evitar reservas en el pasado o dentro de ventana de 24h)
*/
if (empty($errores)) {
    // Construimos datetime completo de la reserva (añadimos segundos)
    $fecha_hora_reserva = $fecha_reserva . ' ' . $hora_reserva . ':00';
    $fecha_hora_reserva_obj = new DateTime($fecha_hora_reserva);
    $ahora = new DateTime();
    $manana = new DateTime('tomorrow');
    
    // Si se trata de la misma fecha actual (debería haberse bloqueado antes, pero se revisa por seguridad)
    if ($fecha_reserva === $ahora->format('Y-m-d')) {
        if ($fecha_hora_reserva_obj <= $ahora) {
            $errores[] = "No se puede reservar una hora que ya ha pasado";
        }
    }
    // Si la reserva es para mañana, comprobamos la regla "al menos 24 horas desde ahora"
    else if ($fecha_reserva === $manana->format('Y-m-d')) {
        $hora_limite_manana = clone $ahora;
        $hora_limite_manana->modify('+24 hours');
        
        if ($fecha_hora_reserva_obj < $hora_limite_manana) {
            $errores[] = "Para reservas del día siguiente, la hora debe ser al menos 24 horas después de la hora actual";
        }
    }
}

/*
    --------------- Todo OK: guardamos los datos temporales en sesión y redirigimos al resumen ---------------
    (ResumenDeReserva.php leerá $_SESSION['datos_reserva'] y mostrará la pantalla de confirmación)
*/
$_SESSION['datos_reserva'] = [
    'id_sala' => $id_sala,
    'num_personas' => $num_personas,
    'fecha_reserva' => $fecha_reserva,
    'hora_reserva' => $hora_reserva,
    'mensaje' => $mensaje,
    'metodo_pago' => $metodo_pago
];

header('Location: ../ResumenDeReserva.php');
exit();
?>