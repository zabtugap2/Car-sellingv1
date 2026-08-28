<?php require 'db.php'; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Chatbot</title></head><body>
<h2>Assistant</h2>
<div id="botArea" style="border:1px solid #ccc; padding:8px; height:300px; overflow:auto;"></div>
<input id="userInput" placeholder="Ask about financing, listings, hours...">
<button id="send">Send</button>

<script>
const kb = [
  {q: /financ/i, r: "Use the Financing Estimator link on each car page. Typical interest default is 7%."},
  {q: /available|list/i, r: "You can view current cars on the home page. If none appear, the host hasn't uploaded the CSV yet."},
  {q: /how.*buy/i, r: "Login, message the host, negotiate, then arrange payment & transfer."},
  {q: /.*/, r: "Sorry, I didn't get that. Try: 'financing', 'available', 'how to buy'."}
];

const area = document.getElementById('botArea');
document.getElementById('send').onclick = ()=>{
  const t = document.getElementById('userInput').value.trim();
  if(!t) return;
  area.innerHTML += '<div><strong>You:</strong> '+t+'</div>';
  let ans = "I don't know.";
  for (const k of kb) {
    if (k.q.test(t)) { ans = k.r; break; }
  }
  area.innerHTML += '<div><strong>Bot:</strong> '+ans+'</div>';
  area.scrollTop = area.scrollHeight;
  document.getElementById('userInput').value = '';
};
</script>

<p><a href="index.php">Back</a></p>
</body></html>
