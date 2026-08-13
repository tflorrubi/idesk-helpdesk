<?php
require_once "funciones.php";

$id_ticket = $_POST["id_ticket"];
cerrarTicket($id_ticket);

header("Location: admin_tickets.php");
exit;
