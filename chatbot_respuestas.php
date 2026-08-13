<?php

require 'clasificar.php';
require 'prioridad.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$mensaje = strtolower($data['mensaje']);

$respuesta = "";

// Detectar si quiere crear un ticket
if (strpos($mensaje, "crear ticket") !== false || 
    strpos($mensaje, "levantar ticket") !== false || 
    strpos($mensaje, "abrir ticket") !== false) {

    // Llamar al generador
    $tipo = clasificarTexto($mensaje);
    $prioridad = calcularPrioridad($mensaje);

    $respuesta = "Perfecto. Tu ticket ha sido generado \n".
                 " Tipo: $tipo\n".
                 " Prioridad: $prioridad";

    echo json_encode(["respuesta" => $respuesta, "crear" => true]);
    exit;
}

// Respuestas básicas del bot
if (strpos($mensaje, 'no enciende') !== false) {
    $respuesta = "Entiendo. Es una falla de hardware. ¿Quieres que levante el ticket?";
}
elseif (strpos($mensaje, 'internet') !== false) {
    $respuesta = "Parece un incidente de red. ¿Deseas generar un ticket?";
}
elseif (strpos($mensaje, 'correo') !== false) {
    $respuesta = "Es una falla de software/correo. ¿Quieres levantar el ticket?";
}
else {
    $respuesta = "Gracias. Dame más detalles para poder clasificarlo.";
}

echo json_encode(["respuesta" => $respuesta]);
