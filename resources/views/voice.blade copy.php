<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Assistant Vocal AZ</title>
</head>
<body>
    <h2>🎙️ Parle à AZ</h2>
    <button onclick="startRecognition()">Clique et parle</button>

    <div id="result"></div>

    <script>
        function startRecognition() {
            const recognition = new window.webkitSpeechRecognition();
            recognition.lang = 'fr-FR';
            recognition.onresult = function(event) {
                const voiceText = event.results[0][0].transcript;
                document.getElementById("result").innerText = "Commande reconnue: " + voiceText;

                fetch('/api/voice-command', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json'},
                    body: JSON.stringify({ command: voiceText})
})
.then(res => res.json())
.then(data => {
                    let msg = data.status + '\n';
                    if (data.propositions) {
                        data.propositions.forEach(p => {
                            msg += `🛒 ${p.quantité_suggérée}x ${p.article} chez ${p.fournisseur} à ${p.prix_unitaire}€/unité\n`;
});
}
                    document.getElementById("result").innerText = msg;
});
};
            recognition.start();
}
    </script>
</body>
</html>
