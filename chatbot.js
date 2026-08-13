document.getElementById("chatbot-btn").onclick = () => {
    document.getElementById("chatbot-window").classList.remove("hidden");
};

document.getElementById("chatbot-close").onclick = () => {
    document.getElementById("chatbot-window").classList.add("hidden");
};

document.getElementById("chatbot-send").onclick = enviarMensaje;

function enviarMensaje() {
    let input = document.getElementById("chatbot-input");
    let msg = input.value.trim();
    if (msg === "") return;

    agregarChat("user", msg);
    input.value = "";

    // Enviar al backend
    fetch("chatbot_respuestas.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mensaje: msg })
    })
    .then(res => res.json())
    .then(data => {
        agregarChat("bot", data.respuesta);
    });
}

function agregarChat(tipo, texto) {
    let chat = document.getElementById("chatbot-chat");
    let div = document.createElement("div");
    div.className = tipo;
    div.textContent = texto;
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
}
