<?php 
require_once "funciones.php";
$tickets = obtenerTicketsAdmin();
$tecnicos = obtenerTecnicos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel del Administrador</title>

<style>
    body{
        font-family: "Segoe UI", sans-serif;
        background: #f0f2f5;
        margin: 0;
        padding: 30px;
    }

    h2{
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }

    .container{
        max-width: 1100px;
        margin: auto;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    table{
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    th{
        background: #4a6fa1;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: normal;
    }

    td{
        padding: 12px;
        border-bottom: 1px solid #ddd;
        color: #333;
    }

    tr:hover{
        background: #f9f9f9;
    }

    select{
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
        margin-right: 5px;
    }

    button{
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
    }

    /* Botón Asignar */
    .btn-assign{
        background: #28a745;
        color: white;
    }

    .btn-assign:hover{
        background: #218838;
    }

    /* Botón Cerrar */
    .btn-close{
        background: #dc3545;
        color: white;
    }

    .btn-close:hover{
        background: #c82333;
    }
</style>
</head>
<body>

<div class="container">

<h2>Panel del Administrador</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Acciones</th>
    </tr>

<?php foreach ($tickets as $t): ?>
    <tr>
        <td><?= $t['id']; ?></td>
        <td><?= $t['usuario']; ?></td>
        <td><?= $t['descripcion']; ?></td>
        <td><?= $t['estatus']; ?></td>

        <td>
            <!-- ASIGNAR TECNICO -->
            <form method="POST" action="asignar_tecnico.php" style="display:inline;">
                <input type="hidden" name="id_ticket" value="<?= $t['id'] ?>">
                
                <select name="id_tecnico">
                    <option value="">Elegir técnico</option>
                    <?php foreach ($tecnicos as $tec): ?>
                        <option value="<?= $tec['id'] ?>"><?= $tec['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Asignar</button>
            </form>

            <!-- CERRAR -->
            <?php if ($t['estatus'] != 'Cerrado'): ?>
                <form method="POST" action="cerrar_ticket.php" style="display:inline;">
                    <input type="hidden" name="id_ticket" value="<?= $t['id'] ?>">
                    <button type="submit" style="background:#dc3545;color:white;">Cerrar</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>

</div>

</body>
</html>
