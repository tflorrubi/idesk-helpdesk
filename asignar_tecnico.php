<?php
require_once "funciones.php";

$id_ticket = $_POST["id_ticket"];
$id_tecnico = $_POST["id_tecnico"];

if ($id_tecnico != "") {
    asignarTecnico($id_ticket, $id_tecnico);
}

header("Location: admin_tickets.php");
exit;
