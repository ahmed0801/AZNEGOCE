<table>
    <thead>
        <tr>
            <th>Article</th>
            <th>Direction</th>
            <th>Type</th>
            <th>Référence</th>
            <th>Date</th>
            <th>Quantité</th>
            <th>Montant</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entries as $entry)
<tr>
    <td>{{ $entry->item_name }}</td>
    <td>{{ $entry->direction }}</td>
    <td>{{ $entry->type }}</td>
    <td>{{ $entry->reference }}</td>
    <td>{{ $entry->date }}</td>
    <td>{{ $entry->quantity }}</td>
    <td>{{ number_format($entry->amount, 2, ',', ' ') }} €</td>
    <td>{{ $entry->note }}</td>
</tr>

        @endforeach
    </tbody>
</table>
