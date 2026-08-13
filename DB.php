<?php

function conectar() {
    $host = "localhost";
    $usuario = "root";
    $password = "";
    $base = "idesk";

    $mysqli = new mysqli($host, $usuario, $password, $base);

    if ($mysqli->connect_errno) {
        die("Error de conexión a MySQL: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8");
    return $mysqli;
}

// ===============================
// CONEXIÓN GLOBAL AUTOMÁTICA
// ===============================
$conexion = conectar();

?>
