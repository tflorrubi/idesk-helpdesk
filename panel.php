<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    die("Debes iniciar sesión");
}

require 'db.php';
require 'funciones.php';
?>

<h2>Panel de Tickets - IDESK</h2>

<table border ="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Depto</th>
        <th>Tipo</th>
        <th>Prioridad</th>
        <th>Estatus</th>
        <th>Acción</th>
    </tr>

<?php
$q = $mysqli->query("SELECT * FROM tickets ORDER BY 
    FIELD(prioridad, 'Alta', 'Media', 'Baja'), 
    fecha_creacion DESC
");

while ($ticket = $q->fetch_assoc()):
?>

<tr>
    <td><?= $ticket['id'] ?></td>
    <td><?= $ticket['usuario'] ?></td>
    <td><?= $ticket['departamento'] ?></td>
    <td><?= $ticket['tipo'] ?></td>
    <td style="background:<?= prioridad_color($ticket['prioridad']) ?>;color:white;">
        <?= $ticket['prioridad'] ?>
    </td>
    <td><?= $ticket['estatus'] ?></td>
    <td><a href="ticket.php?id=<?= $ticket['id'] ?>">Ver</a></td>
</tr>

<?php endwhile; ?>
</table>
