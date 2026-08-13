<?php
session_start();

// Validar correo enviado
if (!isset($_POST['correo']) || empty($_POST['correo'])) {
    die("Error: Debes ingresar tu correo.");
}

$correo = $_POST['correo'];

// Conexión
$conexion = new mysqli("localhost", "root", "", "idesk");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Buscar si el correo existe en algún ticket
$sql = "SELECT correo FROM tickets WHERE correo = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    // Usuario válido
    $_SESSION['usuario_correo'] = $correo;

    header("Location: mis_tickets.php");
    exit;
} else {
    echo "<script>alert('Correo no encontrado. Debes haber creado un ticket primero.');</script>";
    echo "<script>window.location='login.php';</script>";
}

$stmt->close();
$conexion->close();
?>
