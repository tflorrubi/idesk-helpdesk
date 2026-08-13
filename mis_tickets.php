<?php
session_start();

// Si no hay login → enviar al usuario al login
if (!isset($_SESSION['usuario_correo'])) {
    header("Location: login.php");
    exit;
}

$correo = $_SESSION['usuario_correo'];

// ---------------------------------------------
// Conexión
// ---------------------------------------------
$conexion = new mysqli("localhost", "root", "", "idesk");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// ---------------------------------------------
// Consulta
// ---------------------------------------------
$sql = "SELECT 
            id,
            usuario,
            correo,
            departamento,
            categoria,
            tipo,
            impacto,
            urgencia,
            prioridad,
            estatus,
            fecha_creacion,
            fecha_actualizacion
        FROM tickets
        WHERE correo = ?
        ORDER BY fecha_creacion DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tickets</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        h2 { text-align: center; }
        a { 
            display: inline-block; 
            margin-bottom: 15px; 
            text-decoration: none; 
            background: red; 
            color: white; 
            padding: 8px 15px; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th {
            background: #004aad;
            color: white;
            padding: 10px;
        }
        td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        tr:nth-child(even) { background: #eef3ff; }
    </style>
</head>
<body>

<h2>Mis Tickets — <?php echo htmlspecialchars($correo); ?></h2>

<a href="logout.php">Cerrar sesión</a>

<table>
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Correo</th>
        <th>Departamento</th>
        <th>Categoría</th>
        <th>Tipo</th>
        <th>Impacto</th>
        <th>Urgencia</th>
        <th>Prioridad</th>
        <th>Estatus</th>
        <th>Fecha Creación</th>
        <th>Fecha Actualización</th>
    </tr>

    <?php
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['usuario']}</td>
                    <td>{$row['correo']}</td>
                    <td>{$row['departamento']}</td>
                    <td>{$row['categoria']}</td>
                    <td>{$row['tipo']}</td>
                    <td>{$row['impacto']}</td>
                    <td>{$row['urgencia']}</td>
                    <td>{$row['prioridad']}</td>
                    <td>{$row['estatus']}</td>
                    <td>{$row['fecha_creacion']}</td>
                    <td>{$row['fecha_actualizacion']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='12' style='text-align:center;'>No hay tickets</td></tr>";
    }
    ?>
</table>

</body>
</html>
