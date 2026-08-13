<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket Enviado • IDESK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root{
            --bg: #f4f6f9;
            --card: #ffffff;
            --text: #2c3e50;
            --muted: #6b7a86;
            --primary: #2f80ed;
            --accent: #3498db;
            --glass: rgba(255,255,255,0.6);
        }
        [data-theme="dark"]{
            --bg: #0b0f14;
            --card: #0f1720;
            --text: #e6eef7;
            --muted: #9aa9bb;
            --primary: #6aa8ff;
            --accent: #4ba3ff;
            --glass: rgba(255,255,255,0.03);
        }

        body{
            margin:0;
            padding:0;
            font-family: "Inter", system-ui, "Segoe UI", Arial;
            background: var(--bg);
            color: var(--text);
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
        }

        .card{
            background:var(--card);
            padding:35px;
            width:360px;
            text-align:center;
            border-radius:14px;
            box-shadow:0 8px 30px rgba(0,0,0,0.12);
            animation: fadeIn .5s ease;
        }

        @keyframes fadeIn{
            from{ opacity:0; transform: translateY(15px); }
            to{ opacity:1; transform: translateY(0); }
        }

        .icon-check{
            width:68px;
            height:68px;
            border-radius:50%;
            background:linear-gradient(135deg,var(--primary),var(--accent));
            display:flex;
            justify-content:center;
            align-items:center;
            margin:0 auto 18px;
            color:white;
            font-size:34px;
            font-weight:bold;
        }

        h2{
            margin:0;
            font-size:22px;
            font-weight:800;
        }

        p{
            font-size:14px;
            margin:10px 0 20px;
            color:var(--muted);
        }

        a.btn{
            display:block;
            padding:12px;
            background:linear-gradient(90deg,var(--primary),var(--accent));
            color:white;
            text-decoration:none;
            font-weight:700;
            border-radius:10px;
            transition:0.3s ease;
        }

        a.btn:hover{
            opacity:0.85;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="icon-check">✔</div>

    <h2>¡Tu ticket ha sido enviado!</h2>

    <p>
        Gracias por contactarnos.  
        El equipo revisará tu solicitud y un técnico será asignado por el Administrador.
    </p>

    <a href="index.php" class="btn">Volver al inicio</a>

</div>

<script>
    // Tema oscuro/claro automático
    (function(){
        const root = document.documentElement;
        const saved = localStorage.getItem('idesk_theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = saved || (prefersDark ? 'dark' : 'light');

        if(theme === 'dark') root.setAttribute('data-theme','dark');
    })();
</script>

</body>
</html>
