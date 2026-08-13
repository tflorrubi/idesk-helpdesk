<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// ------------ CONFIG: ajusta si tu BD tiene otro usuario/clave -------------
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "idesk"; // adapta si tu DB se llama diferente

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    echo json_encode(['texto' => '❌ Error de conexión a la base de datos.']);
    exit;
}

function clean($s) {
    return trim(htmlspecialchars($s, ENT_QUOTES));
}

// Leer mensaje
$raw = isset($_POST['msg']) ? trim($_POST['msg']) : '';
if ($raw === '') {
    echo json_encode(['texto' => '']); exit;
}
$msg_original = $raw;
$msg = mb_strtolower($raw, 'UTF-8');

// Inicializar estado de conversación si no existe
if (!isset($_SESSION['flow'])) $_SESSION['flow'] = null;
if (!isset($_SESSION['ticket_draft'])) $_SESSION['ticket_draft'] = [];

/*
Flujos soportados:
- "nuevo ticket"  => inicia flujo de creación: pide nombre -> correo -> departamento (opcional) -> descripción -> confirma y guarda
- "cancelar" => cancela flujo de creación
- "ticket <n>" or "ver ticket <n>" => muestra ticket
- palabras clave (internet, wifi, impresora...) => respuestas automáticas y ofrece crear ticket
*/

// Respuestas automáticas por palabra clave
$keywords = [
    'internet' => 'Parece un problema de conexión 🌐. ¿Deseas que cree un ticket por esto? (escribe "sí" o "no")',
    'wifi' => 'Parece una falla de WiFi 📶. ¿Creo un ticket? (sí / no)',
    'impresora' => 'Problema con la impresora 🖨️. ¿Levanto ticket? (sí / no)',
    'correo' => 'Problemas con correo electrónico 📧. ¿Genero ticket? (sí / no)',
    'no enciende' => 'Tu equipo no enciende ⚠️. ¿Crear ticket? (sí / no)'
];

// Si el usuario está en flow de creación de ticket, manejar pasos
if ($_SESSION['flow'] === 'creating_ticket') {
    $draft = &$_SESSION['ticket_draft'];

    // Paso: solicitar nombre
    if (!isset($draft['nombre']) || $draft['nombre'] === '') {
        // El mensaje actual lo tomamos como nombre
        $nombre = trim($raw);
        if ($nombre === '') {
            echo json_encode(['texto' => 'Por favor indica tu nombre (ej: Juan Pérez).']);
            exit;
        }
        $draft['nombre'] = clean($nombre);
        echo json_encode(['texto' => 'Perfecto, ' . $draft['nombre'] . ". Ahora por favor escribe tu correo electrónico:"]);
        exit;
    }

    // Paso: correo
    if (!isset($draft['correo']) || $draft['correo'] === '') {
        $correo = trim($raw);
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['texto' => 'El correo que ingresaste no es válido. Escribe un correo válido (ej: usuario@dominio.com)']);
            exit;
        }
        $draft['correo'] = clean($correo);
        echo json_encode(['texto' => 'Gracias. Si quieres, indica el departamento (ej: Soporte, Redes) o escribe "omitir" para continuar:']);
        exit;
    }

    // Paso: departamento (opcional)
    if (!isset($draft['departamento']) || $draft['departamento'] === '') {
        $dep = trim($raw);
        if (mb_strtolower($dep) === 'omitir') {
            $draft['departamento'] = 'General';
            echo json_encode(['texto' => 'Perfecto. Describe ahora tu problema con todos los detalles posible:']);
            exit;
        }
        // aceptar texto como departamento
        if ($dep === '') {
            echo json_encode(['texto' => 'Indica el departamento o escribe "omitir".']);
            exit;
        }
        $draft['departamento'] = clean($dep);
        echo json_encode(['texto' => 'Gracias. Describe ahora tu problema con todos los detalles posibles:']);
        exit;
    }

    // Paso: descripción
    if (!isset($draft['descripcion']) || $draft['descripcion'] === '') {
        $desc = trim($raw);
        if ($desc === '') {
            echo json_encode(['texto' => 'Escribe la descripción de tu problema (esto será lo que el agente vea).']);
            exit;
        }
        $draft['descripcion'] = clean($desc);

        // Opcional: pedir confirmación / permitir editar
        $resumen = "<b>Resumen del ticket:</b><br>"
                 . "👤 Nombre: " . $draft['nombre'] . "<br>"
                 . "📧 Correo: " . $draft['correo'] . "<br>"
                 . "🏢 Departamento: " . $draft['departamento'] . "<br>"
                 . "📝 Descripción: " . nl2br($draft['descripcion']) . "<br><br>"
                 . "¿Confirmas crear este ticket? (escribe 'confirmar' o 'cancelar')";

        echo json_encode(['texto' => $resumen, 'botones' => ['Confirmar','Cancelar']]);
        exit;
    }

    // Paso: esperar confirmación
    $lower = mb_strtolower(trim($raw));
    if (in_array($lower, ['confirmar','si','sí','confirm'])) {
        // Guardar en DB
        $d = $_SESSION['ticket_draft'];
        $stmt = $mysqli->prepare("INSERT INTO tickets (usuario, correo, departamento, categoria, tipo, descripcion, impacto, urgencia, prioridad, estatus, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Abierto', NOW())");
        // Llenar campos opcionales con valores por defecto
        $categoria = 'incidente';
        $tipo = 'Soporte';
        $impacto = 'Medio';
        $urgencia = 'Media';
        $prioridad = 'P3';

        $stmt->bind_param("sssssssss",
            $d['nombre'],
            $d['correo'],
            $d['departamento'],
            $categoria,
            $tipo,
            $d['descripcion'],
            $impacto,
            $urgencia,
            $prioridad
        );
        if ($stmt->execute()) {
            $ticketID = $stmt->insert_id;
            // limpiar flow
            $_SESSION['flow'] = null;
            $_SESSION['ticket_draft'] = [];
            $html = "🎟 <b>Ticket creado con éxito</b><br>Número: <b>$ticketID</b><br>Un agente dará seguimiento. Puedes consultarlo escribiendo <b>ticket $ticketID</b>.";
            echo json_encode(['texto' => $html]);
            exit;
        } else {
            echo json_encode(['texto' => '❌ Error al guardar el ticket. Intenta de nuevo más tarde.']);
            exit;
        }
    } elseif ($lower === 'cancelar' || $lower === 'no') {
        $_SESSION['flow'] = null;
        $_SESSION['ticket_draft'] = [];
        echo json_encode(['texto' => 'Se canceló la creación del ticket. Si deseas, escribe "nuevo ticket" para comenzar de nuevo.']);
        exit;
    } else {
        echo json_encode(['texto' => 'Por favor responde "confirmar" para crear el ticket o "cancelar" para abortar.']);
        exit;
    }
}

// Comandos directos: ver ticket
if (preg_match('/\b(ticket|ver ticket|ver)\s*[:#\-]?\s*([0-9]+)/i', $msg, $m)) {
    $id = intval($m[2]);
    $stmt = $mysqli->prepare("SELECT * FROM tickets WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows>0) {
        $t = $res->fetch_assoc();
        // Construir respuesta con campos relevantes (adaptar nombres de columna)
        $html = "<b>📄 Ticket #{$t['id']}</b><br>";
        $html .= "👤 Usuario: " . htmlspecialchars($t['usuario']) . "<br>";
        $html .= "📧 Correo: " . htmlspecialchars($t['correo']) . "<br>";
        if (!empty($t['departamento'])) $html .= "🏢 Departamento: " . htmlspecialchars($t['departamento']) . "<br>";
        if (!empty($t['categoria'])) $html .= "📂 Categoría: " . htmlspecialchars($t['categoria']) . "<br>";
        if (!empty($t['tipo'])) $html .= "🔧 Tipo: " . htmlspecialchars($t['tipo']) . "<br>";
        if (!empty($t['impacto'])) $html .= "⚠ Impacto: " . htmlspecialchars($t['impacto']) . "<br>";
        if (!empty($t['urgencia'])) $html .= "⏱ Urgencia: " . htmlspecialchars($t['urgencia']) . "<br>";
        if (!empty($t['prioridad'])) $html .= "🎯 Prioridad: " . htmlspecialchars($t['prioridad']) . "<br>";
        $html .= "📝 Descripción: " . nl2br(htmlspecialchars($t['descripcion'])) . "<br>";
        $html .= "📅 Creado: " . htmlspecialchars($t['fecha_creacion']) . "<br>";
        if (!empty($t['fecha_actualizacion'])) $html .= "🔁 Actualizado: " . htmlspecialchars($t['fecha_actualizacion']) . "<br>";
        $html .= "📌 Estatus: <b>" . htmlspecialchars($t['estatus']) . "</b>";
        echo json_encode(['texto' => $html]);
        exit;
    } else {
        echo json_encode(['texto' => "❌ No encontré el ticket con número <b>$id</b>. Verifica el número."]);
        exit;
    }
}

// Si el usuario pide iniciar nuevo ticket
if (preg_match('/\b(nuevo ticket|crear ticket|abrir ticket)\b/i', $msg)) {
    $_SESSION['flow'] = 'creating_ticket';
    $_SESSION['ticket_draft'] = [];
    echo json_encode(['texto' => 'Excelente. Para comenzar necesito tu nombre (ej: Juan Pérez).']);
    exit;
}

// Responder a confirmation buttons
if (in_array($msg, ['confirmar','confirm','sí','si','cancelar','no'])) {
    // Si no estamos en flow, dar contexto
    echo json_encode(['texto' => 'No hay ninguna acción pendiente. Si deseas crear un ticket escribe "nuevo ticket".']);
    exit;
}

// Palabras clave
foreach ($keywords as $k => $resp) {
    if (strpos($msg, $k) !== false) {
        // Ofrecer crear ticket
        echo json_encode(['texto' => $resp, 'botones' => ['Sí','No']]);
        exit;
    }
}

// Menu y saludos
if (preg_match('/\b(hola|buenas|buenos días|buenas tardes|menu|ayuda)\b/i', $msg)) {
    $texto = "👋 Hola — puedo ayudarte con estas acciones:<br>"
           . "- <b>nuevo ticket</b> → crear un ticket paso a paso<br>"
           . "- <b>ticket 5</b> → ver el ticket #5<br>"
           . "- Preguntar por problemas comunes (ej: internet, wifi, impresora)<br>"
           . "¿Qué deseas hacer?";
    echo json_encode(['texto' => $texto, 'botones' => ['Nuevo Ticket','Menu','Hablar con humano']]);
    exit;
}

// Comando 'hablar con humano' (puedes adaptarlo para notificar a un agente)
if (preg_match('/\b(humano|agente|soporte|hablar con humano)\b/i', $msg)) {
    echo json_encode(['texto' => 'He notificado a un agente. Un humano se pondrá en contacto contigo (simulado). Si necesitas crear un ticket puedes escribir "nuevo ticket".']);
    exit;
}

// Respuesta por defecto
echo json_encode(['texto' => '🤖 No entendí tu solicitud. Escribe <b>menu</b> para ver opciones o <b>nuevo ticket</b> para crear uno.']);
exit;
