<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'importation GOLDA</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; background-color: #f8f9fa; padding: 20px; }
        .container { background: #fff; border-radius: 8px; padding: 20px; border: 1px solid #ddd; }
        h2 { color: #2c3e50; }
        .company { font-size: 13px; margin-bottom: 15px; color: #555; }
        .btn { display: inline-block; background: #007bff; color: #fff !important; text-decoration: none; padding: 10px 15px; border-radius: 5px; margin-top: 15px; }
        .footer { margin-top: 25px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bonjour,</h2>

        <p>{!! nl2br(e($messageText)) !!}</p>

        <p>
            Date du rapport : <strong>{{ now()->format('d/m/Y') }}</strong><br>
            Total des articles actifs en stock : <strong>{{ $totalActiveItems }}</strong>
        </p>

        <h3>Résumé de l'importation</h3>
        @foreach ($report['suppliers'] as $supplier)
            <p>
                Fournisseur : <strong>{{ $supplier['name'] }}</strong><br>
                Articles importés/mis à jour : <strong>{{ $supplier['imported'] }}</strong><br>
                Articles désactivés : <strong>{{ $supplier['deactivated'] }}</strong>
                @if (!empty($supplier['errors']))
                    <br>Erreurs :
                    @foreach ($supplier['errors'] as $error)
                        <br>- {{ $error }}
                    @endforeach
                @endif
            </p>
        @endforeach

        @if (!empty($report['errors']))
            <h3>Erreurs globales</h3>
            @foreach ($report['errors'] as $error)
                <p>- {{ $error }}</p>
            @endforeach
        @endif

        <p>Merci pour votre confiance.</p>

        <div class="footer">
            <p>
                Ceci est un message automatique, merci de ne pas y répondre directement.<br>
                Pour toute question, contactez Ahmed à <strong>ahmedarfaoui1600@gmail.com</strong>.
            </p>
        </div>
    </div>
</body>
</html>