{{-- Fragment chargé en AJAX par la page « Statistiques de vente » --}}
<div class="st-tools st-noprint"><input type="text" class="form-control st-q" placeholder="Filtrer le tableau…"><small><b>{{ $fm($d['count'], 0) }}</b> articles actifs en stock sans vente sur la période · <b>{{ $eur($d['value']) }}</b> immobilisés · {{ count($d['rows']) }} plus gros affichés</small></div>
<div class="table-responsive"><table class="st-table">
    <thead><tr><th>Article</th><th>Marque</th><th>Famille</th><th class="num">Stock</th><th class="num">Coût net</th><th class="num">Valeur</th><th>Dernière vente</th><th class="num">Jours</th></tr></thead>
    <tbody>
    @foreach($d['rows'] as $a)
    <tr><td><b>{{ $a['code'] }}</b><div class="st-sub">{{ $a['name'] }}</div></td><td>{{ $a['brand'] }}</td><td>{{ $a['category'] }}</td>
        {!! $num($a['stock'], 0) !!}{!! $num($a['net_cost'], 2) !!}
        <td class="num st-bar st-bar-r" style="--w:{{ $d['rows'][0]['val'] > 0 ? round($a['val'] / $d['rows'][0]['val'] * 100) : 0 }}%" data-v="{{ $a['val'] }}"><b>{{ $fm($a['val']) }}</b></td>
        <td data-v="{{ $a['last_sale'] }}">{{ $a['last_sale'] ? $fr($a['last_sale']) : 'Jamais vendu' }}</td>
        {!! $num($a['idle_days'], 0) !!}</tr>
    @endforeach
    </tbody>
</table></div>
                        
