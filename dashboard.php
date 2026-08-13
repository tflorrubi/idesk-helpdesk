<?php
require 'DB.php';
require 'funciones.php';

$total    = contar("SELECT COUNT(*) FROM tickets");
$abiertos = contar("SELECT COUNT(*) FROM tickets WHERE estatus='Abierto'");
$proceso  = contar("SELECT COUNT(*) FROM tickets WHERE estatus='En Proceso'");
$cerrados = contar("SELECT COUNT(*) FROM tickets WHERE estatus='Cerrado'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - iDesk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body{ background:#f5f7fa; }
        .card{ border-radius:12px; }
        .card:hover{ transform:scale(1.03); transition:0.2s; }
        .titulo{ font-weight:bold; font-size:26px; }
    </style>
</head>
<body class="container py-4">

    <h1 class="mb-4 fw-bold">📊 Dashboard – Service Desk</h1>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card shadow p-3 text-center">
                <h2 class="text-primary titulo"><?php echo $total; ?></h2>
                <p class="m-0">Tickets Totales</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow p-3 text-center">
                <h2 class="text-warning titulo"><?php echo $abiertos; ?></h2>
                <p class="m-0">Abiertos</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow p-3 text-center">
                <h2 class="text-info titulo"><?php echo $proceso; ?></h2>
                <p class="m-0">En Proceso</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow p-3 text-center">
                <h2 class="text-success titulo"><?php echo $cerrados; ?></h2>
                <p class="m-0">Cerrados</p>
            </div>
        </div>

    </div>

</body>
</html>
