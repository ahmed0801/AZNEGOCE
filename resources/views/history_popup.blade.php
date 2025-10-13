<!DOCTYPE html>
<html>
<head>
    <title>Historique - {{ $itemNo }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; padding: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h4>Historique de l'article : {{ $itemNo }}</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>BL</th>
                <th>Prix V HT</th>
                <th>Prix V TTC</th>
                <th>Coût d'achat</th>
            </tr>
        </thead>
        <tbody>
        @forelse($historique as $entry)
            @php
                $prixVente = floatval(str_replace(',', '.', $entry['Prixvente'] ?? 0));
                $prixHT = $prixVente / 1.19;
                $coutAchat = floatval(str_replace(',', '.', $entry['CoutAchat'] ?? 0));
            @endphp
            <tr>
                <td>{{ $entry['DateBL'] ?? '-' }}</td>
                <td>{{ $entry['BLNo'] ?? '-' }}</td>
                <td>{{ number_format($prixHT, 3, ',', ' ') }} DT</td>
                <td>{{ number_format($prixVente, 3, ',', ' ') }} DT</td>
                <td>{{ number_format($coutAchat, 3, ',', ' ') }} DT</td>
            </tr>
        @empty
            <tr><td colspan="5">Aucun historique trouvé.</td></tr>
        @endforelse
    </tbody>
    </table>
</body>
</html>
