<?php require 'funciones.php'; ?> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Ticket • IDESK</title>
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
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            background: var(--bg);
            color:var(--text);
        }

        /* HEADER */
        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:14px 20px;
            background:rgba(255,255,255,0.02);
            border-bottom:1px solid rgba(0,0,0,0.06);
            backdrop-filter: blur(6px);
        }
        .brand{
            display:flex;
            gap:12px;
            align-items:center;
        }
        .logo-wrap{
            width:44px;
            height:44px;
            display:flex;
            justify-content:center;
            align-items:center;
            border-radius:8px;
            background:linear-gradient(135deg,var(--primary),var(--accent));
        }

        .actions .btn-ghost{
            padding:8px 10px;
            border-radius:8px;
            cursor:pointer;
            border:1px solid rgba(0,0,0,0.06);
            background:transparent;
            font-weight:600;
        }

        /* LAYOUT */
        .wrap{
            max-width:980px;
            margin:28px auto;
            padding:0 18px;
            display:grid;
            grid-template-columns: 1fr 360px;
            gap:20px;
        }

        /* CARD */
        .card{
            background:var(--card);
            padding:22px;
            border-radius:12px;
            box-shadow:0 8px 30px rgba(2,6,23,0.06);
        }

        label{
            font-weight:600;
            margin-bottom:6px;
            display:block;
            font-size:13px;
        }

        input, select, textarea{
            width:100%;
            padding:10px 12px;
            border-radius:8px;
            border:1px solid rgba(0,0,0,0.06);
            background:var(--glass);
            font-size:14px;
        }

        textarea{ min-height:120px; }

        .row{
            display:flex;
            gap:12px;
        }
        .col{ flex:1; }

        .btn{
            display:block;
            width:100%;
            padding:12px;
            border-radius:10px;
            border:none;
            background:linear-gradient(90deg,var(--primary),var(--accent));
            color:#fff;
            font-size:15px;
            font-weight:700;
            cursor:pointer;
        }

        @media(max-width:940px){
            .wrap{ grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="brand">
            <div class="logo-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                    <rect x="1" y="1" width="22" height="22" rx="5" fill="white" opacity="0.06"/>
                    <path d="M7 12a5 5 0 0 1 10 0" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
                    <circle cx="12" cy="8" r="1.8" fill="white"/>
                    <rect x="9" y="15" width="6" height="2.4" rx="1.2" fill="white" opacity="0.9"/>
                </svg>
            </div>
            <div>
                <h1 style="margin:0;font-size:15px;">IDESK</h1>
                <p style="margin:0;font-size:12px;color:var(--muted)">Nuevo Ticket</p>
            </div>
        </div>

        <div class="actions">
            <button class="btn-ghost" id="themeToggle">Modo</button>
            <a href="index.php" class="btn-ghost" style="text-decoration:none;">⬅ Volver</a>
        </div>
    </div>

    <!-- MAIN -->
    <main class="wrap">

        <!-- FORM -->
        <section class="card">
            <h2 style="margin-top:0;">Levantar Nuevo Ticket</h2>
            <p style="font-size:13px;color:var(--muted);margin-top:4px;">
                Rellena los datos del ticket. El técnico será asignado posteriormente por el Administrador.
            </p>

            <form method="POST" action="procesar_ticket.php" id="ticketForm">

                <div class="grupo">
                    <label for="usuario">Nombre del usuario *</label>
                    <input id="usuario" name="usuario" type="text" required>
                </div>

                <div class="grupo">
                    <label for="correo">Correo *</label>
                    <input id="correo" name="correo" type="email" required>
                </div>

                <div class="grupo">
                    <label for="departamento">Departamento</label>
                    <input id="departamento" name="departamento" type="text">
                </div>

                <div class="grupo">
                    <label for="tipo">Tipo de problema</label>
                    <select id="tipo" name="tipo">
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Red">Red</option>
                        <option value="Correo">Correo</option>
                        <option value="Seguridad">Seguridad</option>
                        <option value="Aplicación">Aplicación</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="grupo">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                </div>

                <h3 style="font-size:14px;margin-bottom:8px;color:var(--muted)">Clasificación ITIL</h3>

                <div class="row">
                    <div class="col grupo">
                        <label for="impacto">Impacto</label>
                        <select id="impacto" name="impacto">
                            <option value="Bajo">Bajo</option>
                            <option value="Medio">Medio</option>
                            <option value="Alto">Alto</option>
                            <option value="Critico">Crítico</option>
                        </select>
                    </div>

                    <div class="col grupo">
                        <label for="urgencia">Urgencia</label>
                        <select id="urgencia" name="urgencia">
                            <option value="Baja">Baja</option>
                            <option value="Media">Media</option>
                            <option value="Alta">Alta</option>
                            <option value="Critica">Crítica</option>
                        </select>
                    </div>
                </div>

                <!-- SIN CAMPO DE TÉCNICO -->
                <!-- SIN ASIGNACIÓN AUTOMÁTICA -->

                <button type="submit" class="btn">Crear Ticket</button>
            </form>
        </section>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="card">
                <h3 style="margin-top:0;">Información</h3>
                <p style="font-size:13px;color:var(--muted);">
                    El Administrador será el encargado de asignar el técnico más adecuado a cada ticket.
                </p>
            </div>
        </aside>

    </main>

    <script>
        // Tema Claro/Oscuro
        (function(){
            const root = document.documentElement;
            const saved = localStorage.getItem('idesk_theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');

            if(theme === 'dark') root.setAttribute('data-theme','dark');

            document.getElementById('themeToggle').addEventListener('click', ()=>{
                const isDark = root.getAttribute('data-theme') === 'dark';
                if(isDark){
                    root.removeAttribute('data-theme');
                    localStorage.setItem('idesk_theme','light');
                } else {
                    root.setAttribute('data-theme','dark');
                    localStorage.setItem('idesk_theme','dark');
                }
            });
        })();
    </script>

</body>
</html>
