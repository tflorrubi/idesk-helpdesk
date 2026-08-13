<?php

require_once "DB.php"; // tu archivo de conexión
// ========================================
//  LIMPIAR DATOS PARA EVITAR INYECCIÓN
// ========================================
function limpiar($cadena) {
    return htmlspecialchars(trim($cadena), ENT_QUOTES, 'UTF-8');
}

// ========================================
//  REDIRECCIÓN SIMPLE
// ========================================
function redir($url) {
    header(header: "Location: $url");
    exit;
}

// ========================================
//  CONTAR REGISTROS (para dashboard)
// ========================================
function contar($sql) {
    global $conexion;

    $res = $conexion->query($sql);
    if (!$res) return 0;

    $fila = $res->fetch_row();
    return $fila ? intval($fila[0]) : 0;
}

// ========================================
//  CONSULTAR VALOR ÚNICO
// ========================================
function consultarValor($sql) {
    global $conexion;

    $res = $conexion->query($sql);
    if (!$res) return null;

    $fila = $res->fetch_row();
    return $fila ? $fila[0] : null;
}

// ========================================
//  CONSULTA GENERAL SQL (Resultados múltiples)
// ========================================
function consultaSQL($sql) {
    global $conexion;

    $res = $conexion->query($sql);
    if (!$res) return [];

    $datos = [];
    while ($fila = $res->fetch_assoc()) {
        $datos[] = $fila;
    }

    return $datos;
}

// ========================================
//  CALCULA PRIORIDAD AUTOMÁTICA ITIL
// ========================================
function calcular_prioridad($impacto, $urgencia) {
    // Tabla ITIL real: Prioridad = Impacto + Urgencia
    $mapa = [
        "Bajo-Baja"       => "P4",
        "Bajo-Media"      => "P4",
        "Bajo-Alta"       => "P3",
        "Bajo-Critica"    => "P3",

        "Medio-Baja"      => "P4",
        "Medio-Media"     => "P3",
        "Medio-Alta"      => "P2",
        "Medio-Critica"   => "P2",

        "Alto-Baja"       => "P3",
        "Alto-Media"      => "P2",
        "Alto-Alta"       => "P2",
        "Alto-Critica"    => "P1",

        "Critico-Baja"    => "P2",
        "Critico-Media"   => "P1",
        "Critico-Alta"    => "P1",
        "Critico-Critica" => "P1"
    ];

    $llave = "$impacto-$urgencia";
    return $mapa[$llave] ?? "P3";
}

// ========================================
//  FORMATO DE FECHA BONITO
// ========================================
function formatoFecha($fecha) {
    return date("d/m/Y H:i", strtotime($fecha));
}

// ========================================
//  COLOR DE ETIQUETA SEGÚN ESTADO
// ========================================
function estadoColor($estado) {
    return match ($estado) {
        "Abierto" => "warning",
        "En Proceso" => "info",
        "Cerrado" => "success",
        default => "secondary"
    };
}


// ==============================================
// OBTENER TODOS LOS TICKETS
// ==============================================
function obtenerTicketsAdmin() {
    global $conexion;

    $sql = "SELECT t.*, 
           (SELECT nombre FROM usuarios WHERE id = t.tecnico_asignado) AS tecnico
            FROM tickets t
            ORDER BY t.id DESC";

    $resultado = $conexion->query($sql);
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

// ==============================================
// OBTENER TÉCNICOS
// ==============================================
function obtenerTecnicos() {
    global $conexion;

    $sql = "SELECT id, nombre FROM tecnicos WHERE Especialidad='tecnico'";
    $resultado = $conexion->query($sql);
    $tecnicos = [];
    while ($row = $resultado->fetch_assoc()) {
        $tecnicos[] = $row;
    }

    return $tecnicos;
}

// ==============================================
// ASIGNAR TÉCNICO
// ==============================================
function asignarTecnico($id_ticket, $id_tecnico) {
    global $conexion;

    $sql = "UPDATE tickets 
            SET tecnico_asignado = $id_tecnico, fecha_actualizacion = NOW(),
                estatus = 'Asignado'
            WHERE id = $id_ticket";

    return $conexion->query($sql);
}

// ==============================================
// CAMBIAR ESTATUS / CERRAR TICKET
// ==============================================
function cerrarTicket($id_ticket) {
    global $conexion;

    $sql = "UPDATE tickets 
            SET estatus = 'Cerrado', fecha_actualizacion = NOW()
            WHERE id = $id_ticket";

    return $conexion->query($sql);
}

?>
