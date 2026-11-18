{{-- Fragment chargé en AJAX par la page « Statistiques de vente » --}}
<div class="table-responsive"><table class="st-table">
    <thead><tr><th>Vendeur</th><th class="num">CA HT</th><th class="num">Part</th><th class="num">BL</th><th class="num">Clients</th><th class="num">Panier moyen</th><th class="num">Marge</th><th class="num">Marge %</th></tr></thead>
    <tbody>
    @foreach($d as $v)
    <tr><td><b>{{ $v['name'] }}</b></td>
        <td class="num st-bar" style="--w:{{ min(100, $v['share']) }}%" data-v="{{ $v['ca'] }}"><b>{{ $fm($v['ca']) }}</b></td>
        {!! $num($v['share'], 1) !!}{!! $num($v['docs'], 0) !!}{!! $num($v['clients'], 0) !!}{!! $num($v['basket'], 0) !!}{!! $num($v['margin'], 0) !!}{!! $num($v['margin_pc'], 1) !!}</tr>
    @endforeach
    </tbody>
</table></div>
                        
