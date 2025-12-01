<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Règlements</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; color: #333; }
        h1 { text-align: center; font-size: 18px; color: #1a1a1a; margin-bottom: 5px; }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .company-info { float: left; width: 50%; font-size: 10px; }
        .filters { float: right; width: 45%; text-align: right; font-size: 10px; }
        .clearfix { clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #007bff; color: white; padding: 10px; text-align: center; font-size: 11px; }
        td { padding: 8px; border: 1px solid #ddd; font-size: 10.5px; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mode-title { background: #bbdefb; color: #0d47a1; font-weight: bold; font-size: 13px; }
        .total-mode { background: #e3f2fd; font-weight: bold; color: #1976d2; }
        .grand-total { background: #fff3e0; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 40px; text-align: center; font-size: 9px; color: #666; }
    </style>
</head>
<body>

<div class="header">
<h1>{{ $title ?? 'Rapport des Règlements' }}</h1>
    <small>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</small>
</div>

<div class="company-info">
    <strong>{{ $company->name }}</strong><br>
    {{ $company->address }}<br>
    Tél : {{ $company->phone }} | {{ $company->email }}
</div>

<div class="filters">
    <strong>Filtres :</strong><br>
    Du {{ $request->date_from ? \Carbon\Carbon::parse($request->date_from)->format('d/m/Y') : 'Début' }}
    à {{ $request->date_to ? \Carbon\Carbon::parse($request->date_to)->format('d/m/Y') : 'Fin' }}<br>
    Client : {{ $request->customer_id ? (\App\Models\Customer::find($request->customer_id)->name ?? 'Supprimé') : 'Tous' }}<br>
    Mode : {{ $request->payment_mode ?: 'Tous' }}
</div>
<div class="clearfix"></div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Client / Fournisseur</th>
            <th>Document</th>
            <th>Mode</th>
            <th>Compte</th>
            <th class="text-right">Montant</th>
            <th>Lettrage</th>
            <th>Réf.</th>
        </tr>
    </thead>
    <tbody>

        @forelse($paymentsByMode as $mode => $payments)
            <?php $modeTotal = $payments->sum('amount'); ?>

            <!-- Titre du mode de paiement -->
            <tr class="mode-title">
                <td colspan="8">
                    {{ strtoupper($mode) }}
                    <span style="float:right;">
                        {{ $payments->count() }} règlement{{ $payments->count() > 1 ? 's' : '' }}
                        = {{ number_format($modeTotal, 2, ',', ' ') }} €
                    </span>
                </td>
            </tr>

            @foreach($payments as $payment)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>

                    <td>
                        @if($payment->customer)
                            {{ $payment->customer->name }} <em>(Client)</em>
                        @elseif($payment->supplier)
                            {{ $payment->supplier->name }} <em>(Fournisseur)</em>
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        @if($payment->payable)
                            @if($payment->payable_type == 'App\\Models\\Invoice')
                                Facture V {{ $payment->payable->numdoc ?? '' }}
                            @elseif($payment->payable_type == 'App\\Models\\PurchaseInvoice')
                                Facture A {{ $payment->payable->numdoc ?? '' }}
                            @elseif($payment->payable_type == 'App\\Models\\SalesNote')
                                Avoir V {{ $payment->payable->numdoc ?? '' }}
                            @elseif($payment->payable_type == 'App\\Models\\PurchaseNote')
                                Avoir A {{ $payment->payable->numdoc ?? '' }}
                            @else
                                {{ class_basename($payment->payable_type) }}
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $payment->payment_mode }}</td>

                    <!-- COLONNE COMPTE – CODE QUI MARCHE DÉJÀ CHEZ TOI -->
                    <td>
                        @php
                            $account = $payment->account ?? ($payment->paymentMode ? ($payment->paymentMode->debitAccount ?? $payment->paymentMode->creditAccount) : null);
                            $transfer = $payment->transfers->first();
                        @endphp
                        {{ $account ? $account->name . ' (' . $account->account_number . ')' : '-' }}
                        @if($transfer)
                            <br><small style="color:green">= {{ $transfer->toAccount->name }} ({{ $transfer->toAccount->account_number }})</small>
                        @endif
                    </td>
                    <!-- FIN -->

                    <td class="text-right">{{ number_format($payment->amount, 2, ',', ' ') }} €</td>
                    <td>{{ $payment->lettrage_code ?? '-' }}</td>
                    <td>{{ $payment->reference ?? '-' }}</td>
                </tr>
            @endforeach

            <!-- Sous-total par mode -->
            <tr class="total-mode">
                <td colspan="5" class="text-right"><strong>Total {{ $mode }}</strong></td>
                <td class="text-right"><strong>{{ number_format($modeTotal, 2, ',', ' ') }} €</strong></td>
                <td colspan="2"></td>
            </tr>

        @empty
            <tr><td colspan="8" class="text-center">Aucun règlement trouvé.</td></tr>
        @endforelse

        <!-- TOTAL GÉNÉRAL -->
        <tr class="grand-total">
            <td colspan="5" class="text-right"><strong>TOTAL GÉNÉRAL</strong></td>
            <td class="text-right"><strong>{{ number_format($grandTotal, 2, ',', ' ') }} €</strong></td>
            <td colspan="2"></td>
        </tr>

    </tbody>
</table>

<div class="footer">
    © {{ $company->name }} - {{ date('Y') }} | AZ ERP
</div>

</body>
</html>