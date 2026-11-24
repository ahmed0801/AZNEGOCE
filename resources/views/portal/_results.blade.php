@php $canBuy = !$cart['block']; @endphp
@if($browse)
  <div class="home">
    <h1 class="rv" style="--d:0">Bonjour {{ explode(' ', trim($pUser->name))[0] }}.</h1>
    <p class="sub rv" style="--d:1">{{ $customer->name ?? '' }}@if(!empty($options['total'])) — <b class="cu" data-to="{{ (int) $options['total'] }}">{{ number_format($options['total'], 0, ',', ' ') }}</b> références à votre disposition.@endif</p>
    <div class="quick rv" style="--d:2">
      <button type="button" class="qa js-bulkopen"><span class="ico"><svg class="ic"><use href="#i-paste"/></svg></span><span><b>Coller une liste</b><span>Références et quantités, panier prêt en un clic</span></span></button>
      <button type="button" class="qa js-scanopen"><span class="ico"><svg class="ic"><use href="#i-scan"/></svg></span><span><b>Scanner un code-barres</b><span>Avec l'appareil photo de votre téléphone</span></span></button>
      <button type="button" class="qa js-gofav"><span class="ico"><svg class="ic"><use href="#i-star"/></svg></span><span><b>Mes favoris</b><span>{{ $favCount }} article{{ $favCount > 1 ? 's' : '' }} enregistré{{ $favCount > 1 ? 's' : '' }}</span></span></button>
    </div>

    @if(!empty($dash))
    @php
      $fin = $dash['fin']; $m = function ($n) { return number_format($n, 0, ',', ' ') . ' €'; };
      $tel = preg_replace('/[^0-9+]/', '', (string) ($s->phone ?? '')); $wa = preg_replace('/[^0-9]/', '', (string) ($s->whatsapp ?? ''));
    @endphp
    <div class="dash rv" style="--d:3">
      <div class="dash-act">
        @if($cart['count'] > 0)<button type="button" class="dbtn pri js-opencart"><svg class="ic sm"><use href="#i-cart"/></svg>Reprendre mon panier <b>{{ $cart['count'] }}</b></button>@endif
        @if($dash['last'] && $canBuy)<button type="button" class="dbtn js-reorder" data-url="{{ route('portal.order.reorder', ['order' => $dash['last']['id']], false) }}"><svg class="ic sm"><use href="#i-swap"/></svg>Recommander ma dernière commande</button>@endif
        <a class="dbtn" href="{{ route('portal.orders') }}"><svg class="ic sm"><use href="#i-list"/></svg>Suivre mes commandes @if($dash['active'])<b>{{ $dash['active'] }}</b>@endif</a>
        @if($fin)<a class="dbtn" href="{{ route('portal.account') }}"><svg class="ic sm"><use href="#i-clock"/></svg>Mon compte</a>
        <a class="dbtn" href="{{ route('portal.account.invoices', ['f' => 'unpaid']) }}"><svg class="ic sm"><use href="#i-check"/></svg>Factures à régler @if($fin['nUnpaid'])<b class="{{ $fin['late'] ? 'bad' : '' }}">{{ $fin['nUnpaid'] }}</b>@endif</a>@endif
        @if($tel)<a class="dbtn" href="tel:{{ $tel }}"><svg class="ic sm"><use href="#i-user"/></svg>Appeler</a>@endif
        @if(strlen($wa) >= 8)<a class="dbtn" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"><svg class="ic sm"><use href="#i-arrow"/></svg>WhatsApp</a>@endif
      </div>
      <div class="dash-grid">
        @if($fin)
        <div class="dash-card">
          <div class="dc-h">Mon compte <a href="{{ route('portal.account') }}">Détail</a></div>
          <div class="dc-kpis">
            <div><span>Solde</span><b>{{ $m($fin['solde']) }}</b></div>
            <div class="{{ $fin['late'] ? 'bad' : '' }}"><span>À régler</span><b>{{ $m($fin['unpaid']) }}</b>@if($fin['late'])<small>{{ $fin['late'] }} en retard</small>@endif</div>
            <div class="{{ $fin['nDn'] ? 'warn' : '' }}"><span>Livré, non facturé</span><b>{{ $m($fin['dn']) }}</b><small>{{ $fin['nDn'] }} BL</small></div>
          </div>
        </div>
        @endif
        <div class="dash-card">
          <div class="dc-h">Mes dernières commandes <a href="{{ route('portal.orders') }}">Tout voir</a></div>
          @if(count($dash['recent']))@foreach($dash['recent'] as $o)
            <a class="dc-o" href="{{ route('portal.order', ['order' => $o['id']]) }}"><span class="n">{{ $o['number'] }}</span><span class="d">{{ $o['date'] }}</span><span class="t">{{ number_format($o['total'], 2, ',', ' ') }} €</span><span class="os st-{{ $o['status'] }}{{ $o['mod'] ? ' mod' : '' }}">{{ $o['label'] }}</span></a>
          @endforeach@else
            <div class="dc-e">Aucune commande pour l'instant. Votre première commande apparaîtra ici.</div>
          @endif
        </div>
      </div>
    </div>
    @endif

    @if(count($favRows))
      <div class="sect rv" style="--d:3">Vos favoris <small>achats habituels</small></div>
      <div class="parts rv" style="--d:3">@foreach($favRows as $r)@include('portal._part')@endforeach</div>
    @endif

    @if(!empty($options['categories']))
      <div class="sect rv" style="--d:4">Familles <small>choisissez une famille pour affiner par sous-famille</small></div>
      <div class="fams rv" style="--d:4">
        @foreach($options['categories'] as $c)
          <div class="fam">
            <a href="#" class="fam-h js-cat" data-id="{{ $c['id'] }}"><span class="fam-n">{{ $c['name'] }}</span><span class="fam-c">{{ number_format($c['n'], 0, ',', ' ') }}</span><svg class="ic sm fam-a"><use href="#i-arrow"/></svg></a>
            @if(!empty($c['subs']))<div class="fam-s">@foreach(array_slice($c['subs'], 0, 4) as $sb)<a href="#" class="js-sub" data-cat="{{ $c['id'] }}" data-id="{{ $sb['id'] }}">{{ $sb['name'] }}</a>@endforeach @if(count($c['subs']) > 4)<span class="fam-m">+{{ count($c['subs']) - 4 }}</span>@endif</div>@endif
          </div>
        @endforeach
      </div>
    @endif

    @if(!empty($options['brands']))
      <div class="sect rv" style="--d:5">Marques</div>
      <div class="brands rv" style="--d:5">@foreach(array_slice($options['brands'], 0, 40) as $b)<button type="button" class="chipb js-brand" data-id="{{ $b['id'] }}">{{ $b['name'] }}<small>{{ $b['n'] }}</small></button>@endforeach</div>
    @endif
  </div>
@else
  @if(!empty($approx) && $pager->total() > 0)<div class="note warn" style="margin-bottom:12px">Aucun résultat exact : voici les articles les plus proches de votre recherche.</div>@endif
  @if($pager->total() == 0)
    <div class="empty"><b>Aucun article trouvé</b>Vérifiez l'orthographe, essayez une partie de la référence, ou retirez un filtre.
      <div class="acts">
        <button type="button" class="btn2 js-reset">Effacer la recherche</button>
        <button type="button" class="btn2 js-bulkopen"><svg class="ic sm"><use href="#i-paste"/></svg>Coller une liste</button>
        @if($s->phone)<a class="btn2" href="tel:{{ preg_replace('/[^0-9+]/', '', $s->phone) }}">Appeler {{ $s->phone }}</a>@endif
      </div></div>
  @else
    <div class="parts js-parts">@foreach($pager->items() as $r)@include('portal._part')@endforeach</div>
  @endif

  @if(count($equiv))
    <div class="sect">Équivalents et alternatives <small>articles de remplacement liés à votre recherche</small></div>
    <div class="parts js-parts">@foreach($equiv as $r)@php $noEq = true; @endphp @include('portal._part')@endforeach</div>
  @endif

  @if($pager->lastPage() > 1)
    <div class="pager">
      <button type="button" class="js-page" data-page="{{ $pager->currentPage() - 1 }}" @if($pager->currentPage() <= 1) disabled @endif>Précédent</button>
      <span>Page {{ $pager->currentPage() }} sur {{ $pager->lastPage() }}</span>
      <button type="button" class="js-page" data-page="{{ $pager->currentPage() + 1 }}" @if(!$pager->hasMorePages()) disabled @endif>Suivant</button>
    </div>
  @endif
@endif
