{{-- En-tête commun des documents d'achat (même style que les ventes).
     Variables : $company, $barcode, $docTitle, $docNo, $docDate, $supplier, $meta (tableau libellé => valeur) --}}
<div class="header-box">
    <table class="header-table">
        <tr>
            <td class="header-left">
                <img src="{{ public_path($company->logo_path) }}" alt="Logo">
                <p class="address">{{ $company->address }}</p>
                <p>Tél : {{ $company->phone ?? '-' }}</p>
                <p>Email : {{ $company->email ?? '-' }}</p>
            </td>
            <td class="header-right">
                <h2 style="text-align:right;">{{ $docTitle }} N° : {{ $docNo }}</h2>
                <img src="{{ $barcode }}" alt="Code-barres">
                <div class="details">
                    <p><strong>Date :</strong> {{ $docDate ? \Carbon\Carbon::parse($docDate)->format('d/m/Y') : '-' }}</p>
                    <div style="border:1px solid #007bff; border-radius:6px; padding:6px 10px; text-align:center; margin:6px 0; background:#f8fbff;">
                        <div style="font-size:9px; color:#555; text-transform:uppercase; letter-spacing:.5px;">Fournisseur</div>
                        <div style="font-size:13px; font-weight:bold; color:#003f88;">{{ $supplier->name ?? '-' }}</div>
                        @if(!empty($supplier->address))<div style="font-size:11px; color:#555; margin-top:2px;">{{ $supplier->address }}</div>@endif
                        @if(!empty($supplier->city))<div style="font-size:11px; color:#555;">{{ $supplier->city }}</div>@endif
                        @if(!empty($supplier->code))<div style="font-size:10px; color:#444; margin-top:3px; background:#e8f0fe; border-radius:3px; padding:1px 5px; display:inline-block;">N° fournisseur : {{ $supplier->code }}</div>@endif
                    </div>
                    @foreach(($meta ?? []) as $k => $v)
                        @if($v !== null && $v !== '' && $v !== '-')<p style="font-size:10px"><strong>{{ $k }} :</strong> {{ $v }}</p>@endif
                    @endforeach
                </div>
            </td>
        </tr>
    </table>
</div>
