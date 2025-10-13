@foreach($items as $item)
<tr>
    <td>{{ $item['ItemNo'] ?? 'N/A' }}</td>
    <td>{{ $item['Desc'] ?? 'N/A' }}</td>
    <td>{{ $item['Price'] ?? 'N/A' }}</td>
    <td>
        @php
            $vendorName = trim($item['VendorName'] ?? '');
            $displayName = $vendorMapping[$vendorName] ?? $vendorName ?: 'N/A';
        @endphp
        {{ $displayName }}
    </td>
    <td>
        @if ($item['Stock'] > 0)
            ✅ {{ $item['Stock'] }}
        @else
            ❌
        @endif
    </td>
    <td>
        @if ($item['Stock'] > 0)
        <div class="input-group" style="max-width: 200px; margin: auto;">
            <input type="number" id="quantite-{{ $item['ItemNo'] }}" min="1" 
                   class="form-control quantite-input" placeholder="Quantité" style="width: 50px;">
            <button class="btn btn-secondary ml-2 ajouter-panier" data-article="{{ json_encode($item) }}">
                <i class="fa fa-plus"></i>
            </button>
        </div>
        @endif
    </td>
</tr>
@endforeach
