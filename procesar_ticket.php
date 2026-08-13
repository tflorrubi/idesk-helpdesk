<?php 
include 'DB.php';
$con = conectar();

// ===============================
//  1. OBTENER DATOS DEL FORM
// ===============================
$usuario       = $_POST['usuario'];
$correo        = $_POST['correo'];
$departamento  = $_POST['departamento'];
$tipo          = $_POST['tipo'];
$descripcion   = $_POST['descripcion'];
$impacto       = $_POST['impacto'];
$urgencia      = $_POST['urgencia'];

// ===============================
//  2. ASIGNAR CATEGORÍA
// ===============================
$categoria = $tipo;

// ===============================
//  3. CALCULAR PRIORIDAD (ITIL)
// ===============================
function calcularPrioridad($impacto, $urgencia)
{
    $tabla = [
        "Bajo" => [
            "Baja"    => "Baja",
            "Media"   => "Baja",
            "Alta"    => "Media",
            "Critica" => "Media"
        ],
        "Medio" => [
            "Baja"    => "Baja",
            "Media"   => "Media",
            "Alta"    => "Alta",
            "Critica" => "Alta"
        ],
        "Alto" => [
            "Baja"    => "Media",
            "Media"   => "Alta",
            "Alta"    => "Alta",
            "Critica" => "Critica"
        ],
        "Critico" => [
            "Baja"    => "Alta",
            "Media"   => "Alta",
            "Alta"    => "Critica",
            "Critica" => "Critica"
        ]
    ];

    return $tabla[$impacto][$urgencia];
}

$prioridad = calcularPrioridad($impacto, $urgencia);

// ===============================
//  4. ESTATUS INICIAL
// ===============================
$estatus = "Abierto";

// ===============================
//  5. INSERTAR EN BD (CORREGIDO)
// ===============================
$sql = "INSERT INTO tickets 
        (usuario, correo, departamento, categoria, tipo, descripcion, impacto, urgencia, prioridad, estatus, fecha_creacion, fecha_actualizacion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

$stmt = $con->prepare($sql);
$stmt->bind_param("ssssssssss",
    $usuario,
    $correo,
    $departamento,
    $categoria,
    $tipo,
    $descripcion,
    $impacto,
    $urgencia,
    $prioridad,
    $estatus
);

// ===============================
//  6. RESPUESTA AMIGABLE
// ===============================
if ($stmt->execute()) {
    header("Location: ticket_enviado.php");
    exit;
} else {
    echo "ERROR: " . $stmt->error;
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket Enviado • IDESK</title>
<style>
    body{
        font-family: "Segoe UI", sans-serif;
        background: #f0f4f8;
        margin:0;
        padding:0;
        display:flex;
        justify-content:center;
        align-items:center;
        height:100vh;
    }

    .card{
        background:white;
        padding:40px;
        width:420px;
        border-radius:18px;
        text-align:center;
        box-shadow:0 10px 35px rgba(0,0,0,0.1);
        animation: fadeIn 0.7s ease;
    }

    @keyframes fadeIn {
        from {opacity:0; transform: translateY(10px);}
        to   {opacity:1; transform: translateY(0);}
    }

    .logo{
        width:70px;
        height:70px;
        background:linear-gradient(135deg,#2f80ed,#3498db);
        border-radius:50%;
        display:flex;
        justify-content:center;
        align-items:center;
        margin:0 auto 20px auto;
        color:white;
        font-size:35px;
        font-weight:bold;
        box-shadow:0 5px 20px rgba(47,128,237,0.4);
    }

    h1{
        color:#2c3e50;
        margin-bottom:10px;
    }

    p{
        color:#555;
        font-size:15px;
        margin-top:0;
        margin-bottom:20px;
    }

    .btn{
        display:inline-block;
        margin-top:15px;
        padding:12px 20px;
        background:#2f80ed;
        color:white;
        text-decoration:none;
        border-radius:10px;
        font-weight:600;
        transition:0.3s;
    }

    .btn:hover{
        background:#1c6fd1;
    }
</style>
</head>
<body>

<div class="card">
    <div class="logo">✓</div>

    <?php if ($exito): ?>
        <h1>¡Tu ticket ha sido enviado!</h1>
        <p>Gracias <strong><?= htmlspecialchars($usuario) ?></strong>, tu solicitud ha sido registrada exitosamente.</p>
        <p>Un administrador revisará tu ticket y asignará un técnico pronto.</p>
    <?php else: ?>
        <h1 style="color:#c0392b;">Ocurrió un error</h1>
        <p>No fue posible registrar tu ticket. Inténtalo más tarde.</p>
    <?php endif; ?>

    <a href="index.php" class="btn">Volver al inicio</a>
</div>

</body>
</html>
