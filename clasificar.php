<?php

function clasificarTexto($mensaje) {
    $msg = strtolower($mensaje);

    // --- Incidentes ---
    $incidente_keywords = [
        'no enciende', 'no prende', 'internet', 'correo', 'wifi',
        'error', 'falla', 'lento', 'congelado', 'pantalla azul', 
        'no imprime', 'apagado', 'reinicia'
    ];

    // --- Problemas ---
    $problema_keywords = [
        'siempre', 'todas las semanas', 'repetido', 'frecuente', 
        'varios usuarios', 'todo el departamento', 'muchos equipos'
    ];

    // --- Solicitudes ---
    $solicitud_keywords = [
        'crear usuario', 'nuevo usuario', 'instalar', 'acceso', 
        'permiso', 'agregar', 'necesito software'
    ];

    // --- Cambios ---
    $cambio_keywords = [
        'actualizar servidor', 'mover', 'cambiar', 'upgrade',
        'instalar red', 'nuevo equipo'
    ];

    foreach ($incidente_keywords as $k) {
        if (strpos($msg, $k) !== false) return "Incidente";
    }

    foreach ($problema_keywords as $k) {
        if (strpos($msg, $k) !== false) return "Problema";
    }

    foreach ($solicitud_keywords as $k) {
        if (strpos($msg, $k) !== false) return "Solicitud";
    }

    foreach ($cambio_keywords as $k) {
        if (strpos($msg, $k) !== false) return "Cambio";
    }

    return "Incidente"; // default
}
