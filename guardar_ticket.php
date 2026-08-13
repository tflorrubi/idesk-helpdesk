<?php
require 'clasificar.php';
require 'prioridad.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$mensaje = strtolower($data['mensaje']);

$respuesta = "";

// Palabras que indican confirmación
$confirmaciones = ['sí', 'si', 'ok', 'va', 'dale', 'hazlo', 'crear', 'levantar'];

// Si el usuario dice algo como "sí" después de haber descrito el problema
if (in_array($mensaje, $confirmaciones)) {
    
    // Llamamos al generador del ticket REAL
    $ticketData = json_decode(
        file_get_contents("http://localhost/IDESK/generar_ticket.php"),
        true
    );

    if ($ticketData['status'] === 'ok') {
        $respuesta =
            "🎫 *Ticket generado exitosamente*\n" .
            "ID: " . $ticketData['ticket'] . "\n" .
            "Tipo: " . $ticketData['tipo'] . "\n" .
            "Prioridad: " . $ticketData['prioridad'] . "\n\n" .
            "Un técnico se pondrá en contacto.";
    } else {
        $respuesta = "Hubo un problema al generar el ticket 😔";
    }

    echo json_encode(["respuesta" => $respuesta]);
    exit;
}

// Si describe un problema → proponemos crear ticket
$tipo = clasificarTexto($mensaje);
$prioridad = calcularPrioridad($mensaje);

$respuesta =
    "Entiendo. He analizado tu mensaje.\n" .
    "📌 Tipo detectado: *$tipo*\n" .
    "🔥 Prioridad sugerida: *$prioridad*\n\n" .
    "¿Quieres que genere el ticket? (sí / no)";

echo json_encode(["respuesta" => $respuesta]);
