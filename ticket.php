<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    die("Debes iniciar sesión");
}

require 'db.php';
require 'funciones.php';

$id = intval($_GET['id']);

$q = $mysqli->prepare("SELECT * FROM tickets WHERE id = ?");
$q->bind_param("i", $id);
$q->execute();
$ticket = $q->get_result()->fetch_assoc();
?>

<h2>Ticket #<?= $ticket['id'] ?></h2>

<p><b>Usuario:</b> <?= $ticket['usuario'] ?></p>
<p><b>Correo:</b> <?= $ticket['correo'] ?></p>
<p><b>Departamento:</b> <?= $ticket['departamento'] ?></p>
<p><b>Tipo:</b> <?= $ticket['tipo'] ?></p>
<p><b>Prioridad:</b> <?= $ticket['prioridad'] ?></p>
<p><b>Estatus:</b> <?= $ticket['estatus'] ?></p>
<p><b>Descripción:</b><br><?= nl2br($ticket['descripcion']) ?></p>

<a href="panel.php">← Volver</a>
