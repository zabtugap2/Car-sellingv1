<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$current_user_id = $_SESSION['user_id'];

// Determine receiver:
// If user clicked "message host" we give them the first host user in DB
$receiver_id = null;
if (isset($_GET['to_host'])) {
    $stmt = $pdo->query("SELECT id FROM users WHERE role='host' ORDER BY id LIMIT 1");
    $host = $stmt->fetch();
    if ($host) $receiver_id = $host['id'];
}
if (isset($_GET['to'])) {
    $receiver_id = intval($_GET['to']);
}
if (!$receiver_id) {
    // if host, choose first user as default
    if ($_SESSION['role'] === 'host') {
        $stmt = $pdo->query("SELECT id FROM users WHERE role='user' ORDER BY id LIMIT 1");
        $u = $stmt->fetch();
        if ($u) $receiver_id = $u['id'];
    }
}
if (!$receiver_id) die('No chat partner available.');

?>
<!doctype html><html><head><meta charset="utf-8"><title>Chat</title></head>
<body>
<h2>Chat</h2>
<p>You: <?=htmlspecialchars($_SESSION['name'])?> | Chatting with user id <?= $receiver_id ?></p>

<div id="messages" style="border:1px solid #ccc; height:300px; overflow:auto; padding:8px;"></div>

<form id="msgForm">
  <input type="hidden" id="to" value="<?=$receiver_id?>">
  <input id="msg" autocomplete="off" style="width:70%;">
  <button type="submit">Send</button>
</form>

<script>
const currentId = <?=json_encode($current_user_id)?>;
const toId = <?=json_encode($receiver_id)?>;
const messagesDiv = document.getElementById('messages');

async function fetchMessages(){
  const res = await fetch('get_messages.php?a=' + currentId + '&b=' + toId);
  const data = await res.json();
  messagesDiv.innerHTML = data.map(m => {
    const who = m.sender_id == currentId ? 'You' : 'Them';
    return `<div><strong>${who}:</strong> ${escapeHtml(m.message)} <small style="color:#888">(${m.created_at})</small></div>`;
  }).join('');
  messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

document.getElementById('msgForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const msg = document.getElementById('msg').value.trim();
  if(!msg) return;
  await fetch('send_message.php', {
    method:'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'to=' + encodeURIComponent(toId) + '&message=' + encodeURIComponent(msg)
  });
  document.getElementById('msg').value = '';
  fetchMessages();
});

function escapeHtml(s){ return s.replace(/[&<>"]/g, c=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' })[c]);}

// poll every 2 sec
fetchMessages();
setInterval(fetchMessages, 2000);
</script>

</body></html>
