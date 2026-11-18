{{-- Fragment chargé en AJAX par la page « Statistiques de vente » --}}
<div class="st-tools st-noprint"><input type="text" class="form-control st-q" placeholder="Filtrer le tableau…"><small>{{ count($d) }} meilleurs clients</small></div>
<div class="table-responsive"><table class="st-table">
    <thead><tr><th>Client</th><th class="num">CA HT</th><th class="num">Part</th><th class="num">BL</th><th class="num">Panier moyen</th><th class="num">Réf. achetées</th><th class="num">Marge %</th><th>Dernier achat</th></tr></thead>
    <tbody>
    @foreach($d as $c)
    <tr><td><b>{{ $c['name'] }}</b><div class="st-sub">{{ $c['code'] }}</div></td>
        <td class="num st-bar" style="--w:{{ min(100, $c['share'] * 3) }}%" data-v="{{ $c['ca'] }}"><b>{{ $fm($c['ca']) }}</b></td>
        {!! $num($c['share'], 1) !!}{!! $num($c['docs'], 0) !!}{!! $num($c['basket'], 0) !!}{!! $num($c['articles'], 0) !!}{!! $num($c['margin_pc'], 1) !!}
        <td data-v="{{ $c['last_buy'] }}">{{ $fr($c['last_buy']) }}</td></tr>
    @endforeach
    </tbody>
</table></div>
                        
