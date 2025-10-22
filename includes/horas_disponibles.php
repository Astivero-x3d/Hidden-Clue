<?php
// horas_disponibles.php
header('Content-Type: application/json'); // Se devuelve JSON al frontend

// Validamos parámetros POST
if (!isset($_POST['id_sala']) || !isset($_POST['fecha_reserva'])) {
    echo json_encode(['error' => 'Parámetros insuficientes']);
    exit;
}

$id_sala = intval($_POST['id_sala']); // Convertimos id_sala a entero
$fecha_reserva = $_POST['fecha_reserva'];

// Validamos el formato de fecha YYYY-MM-DD
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_reserva)) {
    echo json_encode(['error' => 'Formato de fecha inválido']);
    exit;
}

// Horas posibles (ajustables según disponibilidad del negocio)
$horas_posibles = [
    '09:00', '10:15', '11:30', '12:45', '16:15', '17:30', '18:45', '20:00'
];

require_once '../conexion/conexion.php';

// Consulta a la base de datos para obtener las horas ya reservadas
$stmt = $conexion->prepare('SELECT hora_reserva FROM reservas WHERE id_sala = ? AND fecha_reserva = ?');
$stmt->bind_param('is', $id_sala, $fecha_reserva);
$stmt->execute();
$result = $stmt->get_result();

// Guardamos horas ocupadas en un array
$horas_ocupadas = [];
while ($row = $result->fetch_assoc()) {
    $horas_ocupadas[] = substr($row['hora_reserva'], 0, 5); // Extrae HH:MM
}

$stmt->close();
$conexion->close();

// Fechas actuales
$ahora = new DateTime();
$fecha_seleccionada = new DateTime($fecha_reserva);
$manana = new DateTime('tomorrow');

// Construcción del array de respuesta indicando si la hora está ocupada o no
$horas = [];
foreach ($horas_posibles as $hora) {
    $hora_datetime = DateTime::createFromFormat('Y-m-d H:i', $fecha_reserva . ' ' . $hora);
    
    $ocupada = in_array($hora, $horas_ocupadas);
    
    // Todas las horas de hoy se bloquean para no permitir reserva el mismo día
    if ($fecha_seleccionada->format('Y-m-d') === $ahora->format('Y-m-d')) {
        $ocupada = true;
    }

    // Para mañana, no se puede reservar antes de 24h desde ahora
    else if ($fecha_seleccionada->format('Y-m-d') === $manana->format('Y-m-d')) {
        $hora_limite_manana = clone $ahora;
        $hora_limite_manana->modify('+24 hours');
        
        if ($hora_datetime < $hora_limite_manana) {
            $ocupada = true; // Marcar como ocupada si es antes de la hora actual + 24h
        }
    }
    
    $horas[] = [
        'hora' => $hora,
        'ocupada' => $ocupada,
        'motivo' => $ocupada ? (in_array($hora, $horas_ocupadas) ? 'reservada' : 'no_disponible') : 'disponible'
    ];
}

// Se devuelve JSON con todas las horas y su estado
echo json_encode(['horas' => $horas]);
?>