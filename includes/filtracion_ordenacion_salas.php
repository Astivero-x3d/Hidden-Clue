<?php
// Conexión a la base de datos
require_once '../conexion/conexion.php';

// ---- Obtener parámetros de filtrado y paginación desde la URL ----
// Si no se envía alguno, se asigna un valor por defecto
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : ''; // Buscar por nombre
$dificultad = isset($_GET['dificultad']) ? $_GET['dificultad'] : ''; // Filtrar por dificultad
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'nombre'; // Orden por nombre por defecto
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1; // Página actual (mínimo 1)
$por_pagina = 2; // Número de salas por página

// ---- Construir cláusula WHERE según filtros ----
$where = [];
if ($busqueda !== '') {
    // Se busca coincidencia parcial en el nombre de la sala
    $where[] = "nombre LIKE '%" . $conexion->real_escape_string($busqueda) . "%'";
}
if ($dificultad !== '') {
    // Se filtra por dificultad exacta
    $where[] = "dificultad = '" . $conexion->real_escape_string($dificultad) . "'";
}

// Se une la cláusula WHERE si hay condiciones
$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// ---- Validar orden ----
// Solo se permite ordenar por nombre, precio o duración; si no, se usa 'nombre'
$orden_sql = in_array($orden, ['nombre','precio','duracion']) ? $orden : 'nombre';

// ---- Calcular total de salas según filtros ----
$sql_total = "SELECT COUNT(*) as total FROM salas $where_sql";
$res_total = $conexion->query($sql_total);
$total = $res_total ? intval($res_total->fetch_assoc()['total']) : 0;

// ---- Paginación ----
$offset = ($pagina - 1) * $por_pagina; // Calcula desde qué registro empezar
$sql = "SELECT * FROM salas $where_sql ORDER BY $orden_sql LIMIT $por_pagina OFFSET $offset";
$res = $conexion->query($sql);

// ---- Mostrar las salas filtradas ----
echo '<section class="salas-listado" id="salas-listado">';
if ($res && $res->num_rows > 0) {
    while ($sala = $res->fetch_assoc()) {
        // Cada sala se muestra como un "card"
        echo '<div class="sala-card">';
        echo '<img src="' . htmlspecialchars($sala['imagen']) . '" alt="Imagen de ' . htmlspecialchars($sala['nombre']) . '" class="sala-imagen">';
        echo '<div class="sala-info">';
        echo '<h2>' . htmlspecialchars($sala['nombre']) . '</h2>';
        echo '<p>' . htmlspecialchars($sala['descripcion']) . '</p>';
        echo '<ul>';
        echo '<li><strong>Dificultad:</strong> ' . htmlspecialchars($sala['dificultad']) . '</li>';
        echo '<li><strong>Duración:</strong> ' . htmlspecialchars($sala['duracion']) . ' min</li>';
        echo '<li><strong>Precio:</strong> ' . htmlspecialchars($sala['precio']) . ' €</li>';
        echo '<li><strong>Jugadores:</strong> ' . htmlspecialchars($sala['jugadores_min']) . ' - ' . htmlspecialchars($sala['jugadores_max']) . '</li>';
        echo '</ul>';
        echo '</div>';
        echo '</div>';
    }
} else {
    // Si no hay salas, se muestra mensaje
    echo '<p>No hay salas disponibles.</p>';
}
echo '</section>';

// ---- Generar botones de paginación ----
$total_paginas = ceil($total / $por_pagina);
echo '<div class="paginacion" id="paginacion">';
if ($pagina > 1) {
    // Botón de página anterior
    echo '<button class="btn-pagina btn-prev" data-pagina="' . ($pagina - 1) . '" aria-label="Anterior"></button>';
}
if ($pagina < $total_paginas) {
    // Botón de página siguiente
    echo '<button class="btn-pagina btn-next" data-pagina="' . ($pagina + 1) . '" aria-label="Siguiente"></button>';
}
echo '</div>';