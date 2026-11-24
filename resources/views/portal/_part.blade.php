@php
  $st = $r['stock']; $p = $r['price'];
  $stLabel = $st['label'] ?: ($st['state'] === 'out' ? 'Rupture' : 'Disponible');
  $buy = (isset($canBuy) ? $canBuy : true) && ($r['can_buy'] ?? true);
@endphp
<article class="part {{ $st['state'] === 'out' ? 'is-out' : '' }}" data-id="{{ $r['id'] }}">
  <div class="c-ref">
    <button type="button" class="plate js-open" data-id="{{ $r['id'] }}" title="Voir la fiche">{{ $r['code'] }}</button>
    @if($r['brand'])<span class="brand">{{ $r['brand'] }}</span>@endif
  </div>
  <div class="c-main">
    <button type="button" class="name js-open" data-id="{{ $r['id'] }}">{{ $r['name'] }}</button>
    <div class="meta">
      @if($r['category'])<span>{{ $r['category'] }}</span>@endif
      @foreach($r['alt_refs'] as $alt)<span class="tec">TecDoc {{ $alt }}</span>@endforeach
    </div>
  </div>
  <div class="c-stock"><span class="stk {{ $st['state'] }}"><i></i>{{ $stLabel }}</span></div>
  <div class="c-price">
    @if($p)
      <div class="net">{{ number_format($p['net_ht'], 2, ',', ' ') }}<small>€ HT</small></div>
      <div class="ttc">{{ number_format($p['net_ttc'], 2, ',', ' ') }} € TTC</div>
      @if($p['discount'] > 0 && $p['list_ht'] > $p['net_ht'])
        <div class="was"><s>{{ number_format($p['list_ht'], 2, ',', ' ') }}</s><span class="tag off">−{{ rtrim(rtrim(number_format($p['discount'], 1, ',', ''), '0'), ',') }} %</span></div>
      @elseif(!empty($p['prix_net']))
        <div class="was"><span class="tag pn">Prix net</span></div>
      @endif
    @else
      <div class="ask">Prix sur demande</div>
    @endif
  </div>
  <div class="c-buy">
    @if($buy)
      <div class="qty"><button type="button" class="js-dec" aria-label="Moins">−</button><input type="number" inputmode="numeric" min="1" max="9999" value="1" class="js-qty" aria-label="Quantité"><button type="button" class="js-inc" aria-label="Plus">+</button></div>
      <button type="button" class="add js-add" data-id="{{ $r['id'] }}"><svg class="ic sm"><use href="#i-plus"/></svg>Ajouter</button>
    @endif
    <button type="button" class="fav js-fav {{ $r['fav'] ? 'on' : '' }}" data-id="{{ $r['id'] }}" aria-label="Favori" aria-pressed="{{ $r['fav'] ? 'true' : 'false' }}"><svg class="ic"><use href="#i-star"/></svg></button>
    <div class="incart" @if(!$r['in_cart']) hidden @endif>{{ $r['in_cart'] }} dans le panier</div>
  </div>
  @if($r['equivalents'] > 0 && empty($noEq))
    <div class="c-eq">
      <button type="button" class="eqbtn js-eq" data-id="{{ $r['id'] }}"><svg class="ic sm"><use href="#i-swap"/></svg>{{ $r['equivalents'] }} équivalent{{ $r['equivalents'] > 1 ? 's' : '' }}</button>
      @if($st['state'] === 'out')<span class="eqhint">En rupture : des alternatives existent.</span>@endif
    </div>
    <div class="eqbox" hidden></div>
  @endif
</article>
