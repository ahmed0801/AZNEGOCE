<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de livraison {{ $deliveryNote->numdoc }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; background-color: #f8f9fa; padding: 20px; }
        .container { background: #fff; border-radius: 8px; padding: 20px; border: 1px solid #ddd; }
        h2 { color: #2c3e50; }
        .company { font-size: 13px; margin-bottom: 15px; color: #555; }
        .btn { display: inline-block; background: #28a745; color: #fff !important; text-decoration: none; padding: 10px 15px; border-radius: 5px; margin-top: 15px; }
        .footer { margin-top: 25px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bonjour {{ $deliveryNote->customer_name ?? 'Client' }},</h2>

        {{-- Message personnalisé --}}
        <p>{!! nl2br(e($messageText)) !!}</p>

        <p>
            Référence BL : <strong>{{ $deliveryNote->numdoc }}</strong><br>
            Date : <strong>{{ optional($deliveryNote->created_at)->format('d/m/Y') ?? '-' }}</strong>
        </p>

        <div class="company">
            <p><strong>{{ $company->name }}</strong></p>
            <p>{{ $company->address }}</p>
            <p>Tél : {{ $company->phone }} | Email : {{ $company->email }}</p>
        </div>

        <p>Le bon de livraison est joint en PDF. Merci pour votre confiance.</p>

        <a href="#" class="btn">Suivre ma livraison</a>

        <div class="footer">
            <p>
                Ceci est un message automatique, merci de ne pas y répondre directement.<br>
                Pour toute question, contactez-nous à <strong>{{ $company->email }}</strong>.
            </p>
        </div>
    </div>
</body>
</html>