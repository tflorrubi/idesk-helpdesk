<?php

function calcularPrioridad($mensaje) {
    $msg = strtolower($mensaje);

    // Impacto
    $impacto = "Bajo";

    if (strpos($msg, 'toda el área') !== false || strpos($msg, 'todos') !== false) {
        $impacto = "Alto";
    } elseif (strpos($msg, 'usuario') !== false || strpos($msg, 'solo yo') !== false) {
        $impacto = "Medio";
    }

    // Urgencia
    $urgencia = "Media";

    if (strpos($msg, 'no puedo trabajar') !== false || strpos($msg, 'detenido') !== false) {
        $urgencia = "Alta";
    } elseif (strpos($msg, 'molesto') !== false || strpos($msg, 'lento') !== false) {
        $urgencia = "Baja";
    }

    // Tabla prioridad
    if ($impacto == "Alto" && $urgencia == "Alta") return "P1";
    if ($impacto == "Medio" && $urgencia == "Alta") return "P2";
    if ($impacto == "Bajo" && $urgencia == "Alta") return "P3";

    return "P4";
}
