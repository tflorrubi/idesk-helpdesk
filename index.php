<?php require 'DB.php'; ?>
<?php require 'funciones.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mesa de Ayuda - iDesk México</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f5f7fa;
            font-family: Arial, sans-serif;
        }
        .hero {
            background: linear-gradient(90deg, #003366, #006699);
            color: white;
            padding: 40px;
            border-radius: 12px;
        }
        .card-opcion {
            padding: 25px;
            border-radius: 12px;
            transition: 0.2s;
        }
        .card-opcion:hover {
            transform: scale(1.05);
            cursor: pointer;
        }
        .logo {
            width: 160px;
        }
    </style>
</head>
<body class="container py-4">

  <!-- ENCABEZADO -->
<div class="hero mb-4">
    <div class="d-flex align-items-center">
        <img src="logo.png" class="logo me-3">
        <div>
            <h1 class="fw-bold m-0">Mesa de Ayuda – iDesk México</h1>
            <p class="m-0">Soporte Técnico | Hardware | Software | Redes | Seguridad</p>
        </div>
    </div>
</div>


    <!-- MENSAJE DE CONFIRMACIÓN -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success">✔ Ticket creado correctamente.</div>
    <?php endif; ?>

    <!-- OPCIONES PRINCIPALES -->
    <div class="row">

        <!-- 1. NUEVO TICKET -->
        <div class="col-md-3 mb-3">
            <a href="nuevo_ticket.php" class="text-decoration-none">
                <div class="card shadow card-opcion text-center">
                    <h2 class="fw-bold">➕ Nuevo Ticket</h2>
                    <p>Levanta un incidente, problema o solicitud.</p>
                </div>
            </a>
        </div>

        <!-- 2. MIS TICKETS -->
        <div class="col-md-3 mb-3">
            <a href="mis_tickets.php" class="text-decoration-none">
                <div class="card shadow card-opcion text-center">
                    <h2 class="fw-bold">📄 Mis Tickets</h2>
                    <p>Consulta el estado de tus reportes.</p>
                </div>
            </a>
        </div>

        <!-- 3. CHATBOT -->
        <div class="col-md-3 mb-3">
            <a href="chatbot.php" class="text-decoration-none">
                <div class="card shadow card-opcion text-center">
                    <h2 class="fw-bold">🤖Asistente IA</h2>
                    <p>Levanta tickets por conversación.</p>
                </div>
            </a>
        </div>

        <!-- 4. DASHBOARD -->
        <div class="col-md-3 mb-3">
            <a href="dashboard.php" class="text-decoration-none">
                <div class="card shadow card-opcion text-center">
                    <h2 class="fw-bold">📊 Dashboard</h2>
                    <p>Visualiza el estado del Service Desk.</p>
                </div>
            </a>
        </div>

    </div>

</body>
</html>


