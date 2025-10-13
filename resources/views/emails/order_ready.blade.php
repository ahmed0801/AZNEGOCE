<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande prête - Facture {{ $invoice->numdoc }}</title>
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
        <h2>Bonjour {{ $invoice->customer->name ?? 'Client' }},</h2>

        {{-- Message accompagnant --}}
        <p>{!! nl2br(e($messageText)) !!}</p>

        <p>
            Référence facture : <strong>{{ $invoice->numdoc }}</strong><br>
            Date : <strong>{{ optional($invoice->invoice_date ?? $invoice->created_at)->format('d/m/Y') ?? '-' }}</strong>
        </p>

        <div class="company">
            <p><strong>{{ $company->name }}</strong></p>
            <p>{{ $company->address }}</p>
            <p>Tél : {{ $company->phone }} | Email : {{ $company->email }}</p>
        </div>

        <div class="footer">
            <p>
                Ceci est un message automatique, merci de ne pas y répondre directement.<br>
                Pour toute question, contactez-nous à <strong>{{ $company->email }}</strong>.
            </p>
        </div>
    </div>
</body>
</html>
