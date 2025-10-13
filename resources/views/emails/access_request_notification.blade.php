<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouvelle Demande d'Accès</title>
    <style>
        .button {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            color:rgb(145, 2, 2);
            background-color:rgb(143, 185, 231);
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h3>Nouvelle Demande d'Accès au site B2B</h3>
    <p><strong>Nom du Client :</strong> {{ $data['clientName'] }}</p>
    <p><strong>Nom du Demandeur :</strong> {{ $data['requesterName'] }}</p>
    <p><strong>Numéro WhatsApp :</strong> {{ $data['whatsappNumber'] }}</p>
    <p><strong>Déjà Client ? :</strong> {{ $data['isClient'] }}</p>
    <p>Veuillez traiter cette demande dès que possible.</p>
    <p>
        <a href="https://premagros.tn/admin/login" class="button">Consulter la Demande</a>
    </p>
</body>
</html>
