@php $canBuy = !$cart['block']; @endphp
@if($browse)
  <div class="home">
    @php
      $hour = (int) date('G'); $first = explode(' ', trim($pUser->name))[0];
      $today = ''; try { $today = ucfirst(\Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM')); } catch (\Throwable $e) { }
      $slots = ''; for ($i = 0; $i < 28; $i++) { $slots .= '<line x1="120" y1="40" x2="120" y2="56" transform="rotate(' . ($i * 360 / 28) . ' 120 120)"/>'; }
      $bolts = ''; for ($i = 0; $i < 5; $i++) { $bolts .= '<circle cx="120" cy="100" r="5.5" transform="rotate(' . ($i * 72) . ' 120 120)"/>'; }
    @endphp
    <section class="hero rv" style="--d:0">
      <div class="hero-txt">
        <p class="hero-day">{{ $today }}</p>
        <h1>{{ $hour >= 18 || $hour < 5 ? 'Bonsoir' : 'Bonjour' }} {{ $first }}.</h1>
        <p class="sub">{{ $customer->name ?? '' }}@if(!empty($options['total'])) — <b class="cu" data-to="{{ (int) $options['total'] }}">{{ number_format($options['total'], 0, ',', ' ') }}</b> références à votre disposition.@endif</p>
        <div class="hero-cta">
          <a class="cta-main" href="{{ route('portal.quote.new') }}"><svg class="ic"><use href="#i-paste"/></svg><span><b>Demander un devis</b></span></a>
          <a class="cta-sec" href="{{ route('portal.orders') }}"><svg class="ic sm"><use href="#i-list"/></svg>Suivre mes commandes</a>
        </div>
      </div>
      <svg class="hero-disc" viewBox="0 0 240 240" aria-hidden="true" focusable="false">
        <g class="disc">
          <circle cx="120" cy="120" r="112"/><circle cx="120" cy="120" r="102"/>
          <g class="slots">{!! $slots !!}</g>
          <circle cx="120" cy="120" r="64" class="ring"/><circle cx="120" cy="120" r="34" class="hub"/><g class="bolts">{!! $bolts !!}</g><circle cx="120" cy="120" r="12" class="cap"/>
        </g>
        <path class="caliper" d="M 226 78 A 112 112 0 0 1 226 162 L 206 154 A 90 90 0 0 0 206 86 Z"/>
      </svg>
    </section>
    <div class="quick rv" style="--d:2" role="group" aria-label="Actions rapides">
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
      @php $qNo = ['answered' => [], 'erp' => [], 'count' => 0]; try { $qNo = \App\Services\PortalQuoteNotices::for(request()->attributes->get('portal_user')->customer_id); } catch (\Throwable $e) { } @endphp
      @php $pNo = ['count' => 0, 'list' => []]; $pcs = request()->attributes->get('portal_customer_settings'); if (($s->show_prices ?? false) && (!$pcs || $pcs->show_prices)) { try { $pNo = \App\Services\PortalPromoNotices::for(request()->attributes->get('portal_user')->customer, $s); } catch (\Throwable $e) { } } @endphp
      @php
        $qAns = 0; try { $qAns = \App\Models\PortalQuote::where('customer_id', request()->attributes->get('portal_user')->customer_id)->where('status', 'answered')->count(); } catch (\Throwable $e) { }
        $hl = []; try { $hl = \App\Services\PortalPromoNotices::highlights(request()->attributes->get('portal_user'), $s, $pcs, 6); } catch (\Throwable $e) { }
        $tk = [];
        foreach (array_slice($qNo['erp'], 0, 3) as $qe) { $tk[] = ['tone' => 'brand', 'ico' => 'i-paste', 'k' => 'Nouveau · de notre équipe', 't' => 'Devis ' . $qe->numdoc, 'm' => number_format($qe->total_ttc, 2, ',', ' ') . ' € TTC', 'url' => route('portal.quotes'), 'new' => true]; }
        foreach (array_slice($qNo['answered'], 0, 3) as $qa) { $tk[] = ['tone' => 'ok', 'ico' => 'i-check', 'k' => 'Votre demande a reçu une réponse', 't' => 'Devis ' . $qa->number . ' prêt', 'm' => number_format($qa->total_ttc, 2, ',', ' ') . ' € TTC' . ($qa->valid_until ? ' · valable jusqu\'au ' . $qa->valid_until->format('d/m') : ''), 'url' => route('portal.quote', ['id' => $qa->id]), 'new' => false]; }
        foreach (array_slice($pNo['list'], 0, 3) as $pn) { $tk[] = ['tone' => 'promo', 'ico' => 'i-star', 'k' => $pn['is_new'] ? 'Nouvelle promotion' : 'Promotion en cours', 't' => $pn['name'], 'm' => $pn['items'] . ' article' . ($pn['items'] > 1 ? 's' : '') . ' · jusqu\'au ' . $pn['ends'], 'url' => route('portal.promotions') . '#promo-' . $pn['id'], 'new' => $pn['is_new']]; }
        if ($fin && $fin['late']) { $tk[] = ['tone' => 'warn', 'ico' => 'i-clock', 'k' => 'À régler', 't' => $fin['late'] . ' facture' . ($fin['late'] > 1 ? 's' : '') . ' en retard', 'm' => $m($fin['unpaid']) . ' à régler', 'url' => route('portal.account.invoices', ['f' => 'unpaid']), 'new' => false]; }
        if ($cart['count'] > 0) { $tk[] = ['tone' => 'info', 'ico' => 'i-cart', 'k' => 'Panier en attente', 't' => $cart['count'] . ' article' . ($cart['count'] > 1 ? 's' : '') . ' vous attendent', 'm' => 'Reprendre où vous en étiez', 'url' => '', 'new' => false, 'cart' => true]; }
      @endphp
      <div class="sect rv" style="--d:3">Aujourd'hui pour vous</div>
      <div class="todo rv" style="--d:3">
        @foreach($tk as $t)
          @if(!empty($t['cart']))<button type="button" class="tk t-{{ $t['tone'] }} js-opencart">@else<a class="tk t-{{ $t['tone'] }}" href="{{ $t['url'] }}">@endif
            <span class="tk-stub"><svg class="ic"><use href="#{{ $t['ico'] }}"/></svg>@if($t['new'])<i class="tk-dot"></i>@endif</span>
            <span class="tk-body"><small>{{ $t['k'] }}</small><b>{{ $t['t'] }}</b><em>{{ $t['m'] }}</em></span>
            <svg class="ic sm tk-go"><use href="#i-arrow"/></svg>
          @if(!empty($t['cart']))</button>@else</a>@endif
        @endforeach
        @if(!count($tk))<div class="tk t-calm"><span class="tk-stub"><svg class="ic"><use href="#i-check"/></svg></span><span class="tk-body"><small>Tout est à jour</small><b>Rien à traiter pour le moment</b><em>Vos nouveaux devis et promotions apparaîtront ici.</em></span></div>@endif
      </div>
      <div class="kpis rv" style="--d:4">
        <a class="kpi" href="{{ route('portal.orders') }}"><span>Commandes en cours</span><b class="cu" data-to="{{ (int) $dash['active'] }}">{{ (int) $dash['active'] }}</b></a>
        <a class="kpi {{ $qAns ? 'on' : '' }}" href="{{ route('portal.quotes') }}"><span>Devis à valider</span><b class="cu" data-to="{{ (int) $qAns }}">{{ (int) $qAns }}</b></a>
        @if($fin)<a class="kpi {{ $fin['late'] ? 'bad' : '' }}" href="{{ route('portal.account.invoices', ['f' => 'unpaid']) }}"><span>À régler</span><b>{{ $m($fin['unpaid']) }}</b></a>
        <a class="kpi" href="{{ route('portal.account') }}"><span>Solde</span><b>{{ $m($fin['solde']) }}</b></a>@endif
      </div>
      @if(count($hl))
      <div class="sect rv" style="--d:4">En promotion pour vous <small>prix calculés pour votre compte</small> <a class="sect-a" href="{{ route('portal.promotions') }}">Tout voir</a></div>
      <div class="prail rv" style="--d:4">
        @foreach($hl as $h)
          <a class="pc" href="{{ route('portal.promotions') }}#promo-{{ $h['promo_id'] }}">
            <span class="pc-tag">−{{ rtrim(rtrim(number_format($h['discount'], 1, ',', ''), '0'), ',') }} %</span>
            <small class="pc-code">{{ $h['code'] }}@if($h['brand']) · {{ $h['brand'] }}@endif</small>
            <span class="pc-name">{{ $h['name'] }}</span>
            <span class="pc-price"><b>{{ number_format($h['net'], 2, ',', ' ') }} €</b><s>{{ number_format($h['list'], 2, ',', ' ') }}</s></span>
            <small class="pc-end">{{ $h['promo'] }} · jusqu'au {{ $h['ends'] }}</small>
          </a>
        @endforeach
      </div>
      @endif
      <div class="sect rv" style="--d:4">Raccourcis</div>
      <div class="dash-act">
        @if($cart['count'] > 0)<button type="button" class="dbtn pri js-opencart"><svg class="ic sm"><use href="#i-cart"/></svg>Reprendre mon panier <b>{{ $cart['count'] }}</b></button>@endif
        <a class="dbtn" href="{{ route('portal.orders') }}"><svg class="ic sm"><use href="#i-list"/></svg>Suivre mes commandes @if($dash['active'])<b>{{ $dash['active'] }}</b>@endif</a>
        @if($fin)<a class="dbtn" href="{{ route('portal.account') }}"><svg class="ic sm"><use href="#i-clock"/></svg>Mon compte</a>
        <a class="dbtn" href="{{ route('portal.account.invoices', ['f' => 'unpaid']) }}"><svg class="ic sm"><use href="#i-check"/></svg>Factures à régler @if($fin['nUnpaid'])<b class="{{ $fin['late'] ? 'bad' : '' }}">{{ $fin['nUnpaid'] }}</b>@endif</a>@endif
        <a class="dbtn" href="{{ route('portal.quotes') }}"><svg class="ic sm"><use href="#i-list"/></svg>Mes devis @if($qAns)<b>{{ $qAns }}</b>@endif</a>
        @if($pNo['count'])<a class="dbtn promo" href="{{ route('portal.promotions') }}"><svg class="ic sm"><use href="#i-star"/></svg>Promotions <b>{{ $pNo['count'] }}</b></a>@endif
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
          @endforeach
          @else
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
        <a class="btn2 pri" href="{{ route('portal.quote.new', ['name' => request()->query('q')]) }}">Demander un devis pour cette pièce</a>
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
