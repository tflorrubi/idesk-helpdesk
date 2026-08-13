<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>ChatBot iDesk México</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    :root{
        --wh-bg:#ece5dd; --card:#ffffff; --primary:#075e54; --send:#128c7e;
    }
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--wh-bg);display:flex;justify-content:center;align-items:center;height:100vh}
    #chat-container{width:380px;height:600px;background:var(--card);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.18);display:flex;flex-direction:column;overflow:hidden}
    #header{background:var(--primary);color:#fff;padding:14px 12px;display:flex;gap:10px;align-items:center}
    #header img{width:36px;border-radius:50%}
    #mensajes{flex:1;padding:12px;overflow:auto;background:#e5ddd5}
    .msg{max-width:85%;padding:10px;margin:8px 0;border-radius:8px;font-size:14px;line-height:1.3}
    .user{background:#dcf8c6;margin-left:auto;border-radius:10px 6px 6px 10px}
    .bot{background:#fff;border:1px solid #ddd;border-radius:6px 10px 10px 6px}
    .bot .buttons{margin-top:8px;display:flex;flex-wrap:wrap;gap:6px}
    .bot .buttons button{background:var(--send);border:none;color:#fff;padding:8px 10px;border-radius:8px;cursor:pointer;font-size:13px}
    #input-area{display:flex;padding:10px;background:#f0f0f0;align-items:center;gap:8px}
    #texto{flex:1;padding:10px;border-radius:20px;border:1px solid #ccc;outline:none}
    #send-btn{width:44px;height:44px;border-radius:50%;border:none;background:var(--send);color:#fff;font-size:18px;cursor:pointer}
    .small{font-size:13px;color:#555}
    pre{white-space:pre-wrap;word-wrap:break-word;margin:0}
</style>
</head>
<body>

<div id="chat-container">
    <div id="header">
        <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" alt="iDesk">
        <div>
            <div style="font-weight:700">iDesk México</div>
            <div class="small">Soporte técnico — ChatBot</div>
        </div>
    </div>

    <div id="mensajes"></div>

    <div id="input-area">
        <input id="texto" placeholder="Escribe un mensaje (p. ej. 'nuevo ticket', 'ticket 5', 'hola')">
        <button id="send-btn" title="Enviar">➤</button>
    </div>
</div>

<script>
const mensajes = document.getElementById('mensajes');
const input = document.getElementById('texto');
const sendBtn = document.getElementById('send-btn');

/* -----------------------------
   UTILIDADES
------------------------------*/
function addMessageHTML(html, clase='bot') {
    const div = document.createElement('div');
    div.className = 'msg ' + clase;
    div.innerHTML = html;
    mensajes.appendChild(div);
    mensajes.scrollTop = mensajes.scrollHeight;
}

function escapeHtml(s) {
    return s.replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/\n/g,'<br>');
}

function escapeForJS(s) {
    return s.replace(/'/g,"\\'").replace(/"/g,'\\"');
}

/* -----------------------------
   BOTONES INTERACTIVOS
------------------------------*/
function createButtons(botones) {
    const div = document.createElement('div');
    div.className = "buttons";

    botones.forEach(texto => {
        const btn = document.createElement("button");
        btn.textContent = texto;

        btn.addEventListener("click", () => {
            btn.disabled = true;               // evita doble clic
            btn.classList.add("clicked");      // efecto visual
            send(texto);                       // envía al chatbot
        });

        div.appendChild(btn);
    });

    return div;
}

/* -----------------------------
   MOSTRAR MENSAJES DEL BOT
------------------------------*/
function showBot(texto, botones=null) {
    const cont = document.createElement("div");
    cont.className = "msg bot";
    cont.innerHTML = texto;

    if (botones && botones.length) {
        cont.appendChild(createButtons(botones));
    }

    mensajes.appendChild(cont);
    mensajes.scrollTop = mensajes.scrollHeight;
}

/* -----------------------------
   MOSTRAR MENSAJE DEL USUARIO
------------------------------*/
function showUser(texto) {
    addMessageHTML(escapeHtml(texto), 'user');
}

/* -----------------------------
   ENVIAR TEXTO AL BACKEND
------------------------------*/
function send(msg=null) {
    const message = msg ?? input.value.trim();
    if (!message) return;

    showUser(message);
    input.value = '';

    fetch('chatbot_backend.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'msg=' + encodeURIComponent(message)
    })
    .then(r => r.json())
    .then(data => {
        if (data.html) {
            addMessageHTML(data.html, 'bot');
            return;
        }

        if (data.texto) {
            showBot(data.texto, data.botones ?? null);
            return;
        }

        showBot("⚠️ Respuesta inválida del servidor.");
    })
    .catch(err => {
        showBot("❌ Error de conexión con el servidor.");
        console.error(err);
    });
}

/* -----------------------------
   EVENTOS
------------------------------*/
sendBtn.addEventListener('click', ()=> send());
input.addEventListener('keydown', e => { if (e.key === 'Enter') send(); });

/* -----------------------------
   MENSAJE INICIAL
------------------------------*/
showBot(
    '👋 ¡Hola! Soy el ChatBot de <b>iDesk México</b>.<br>' +
    'Escribe <b>nuevo ticket</b>, <b>ticket 5</b> o <b>menu</b>.',
    ['Nuevo Ticket','Menu','Hablar con humano']
);
</script>


</body>
</html>
