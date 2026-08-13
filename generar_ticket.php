<?php
require 'db.php';
require '
funciones.php';
require 'clasificar.php';
require 'prioridad.php';

header('Content-Type: application/json');

// Obtener datos del chatbot
$data = json_decode(file_get_contents("php://input"), true);
$mensaje = limpiar($data['mensaje']);
$usuario = "Chatbot";  // Puedes usar sesión si existe
$correo = "chatbot@idesk.com";
$departamento = "General";

// Clasificación automática
$tipo = clasificarTexto($mensaje);
$prioridad = calcularPrioridad($mensaje);
$descripcion = $mensaje;

// Guardar ticket en la base de datos
$stmt = $mysqli->prepare("INSERT INTO tickets 
(usuario, correo, departamento, tipo, descripcion, prioridad, fecha_creacion, estatus)
VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Abierto')");

$stmt->bind_param(
    "ssssss",
    $usuario,
    $correo,
    $departamento,
    $tipo,
    $descripcion,
    $prioridad
);

if ($stmt->execute()) {
    // Obtener ID del ticket
    $ticket_id = $stmt->insert_id;

    echo json_encode([
        "status" => "ok",
        "ticket" => $ticket_id,
        "tipo" => $tipo,
        "prioridad" => $prioridad
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "No se pudo generar el ticket"
    ]);
}
