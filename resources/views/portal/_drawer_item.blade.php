@php $st = $r['stock']; $p = $r['price']; $stLabel = $st['label'] ?: ($st['state'] === 'out' ? 'Rupture' : 'Disponible'); @endphp
<div class="di-top">
  <span class="plate" style="cursor:text">{{ $r['code'] }}</span>
  @if($r['brand'])<span class="brand">{{ $r['brand'] }}</span>@endif
  <span class="stk {{ $st['state'] }}"><i></i>{{ $stLabel }}</span>
</div>
<h3 class="di-name" style="font-size:1.45rem;margin-top:0">{{ $r['name'] }}</h3>

<div class="di-buy part" data-id="{{ $r['id'] }}" style="display:grid;grid-template-columns:1fr;border:0">
  @if($p)
    <div class="c-price" style="text-align:left">
      <div class="net">{{ number_format($p['net_ht'], 2, ',', ' ') }}<small>€ HT</small></div>
      <div class="ttc">{{ number_format($p['net_ttc'], 2, ',', ' ') }} € TTC</div>
      @if($p['discount'] > 0 && $p['list_ht'] > $p['net_ht'])
        <div class="was" style="justify-content:flex-start">Tarif public <s>{{ number_format($p['list_ht'], 2, ',', ' ') }} €</s><span class="tag off">−{{ rtrim(rtrim(number_format($p['discount'], 1, ',', ''), '0'), ',') }} %</span></div>
      @elseif(!empty($p['prix_net']))<div class="was" style="justify-content:flex-start"><span class="tag pn">Prix net</span></div>@endif
      @if(!empty($p['promo']))<div class="was" style="justify-content:flex-start"><span class="tag promo">Promo jusqu'au {{ $p['promo']['ends'] }}</span></div>@endif
    </div>
  @else<div class="ask">Prix sur demande <a class="link" style="font-size:.8rem" href="{{ route('portal.quote.new', ['ref' => $r['code'], 'name' => $r['name'], 'brand' => $r['brand']]) }}">Demander un devis</a></div>@endif
  <div class="c-buy" style="justify-content:flex-start">
    @if($canBuy && ($r['can_buy'] ?? true))
      <div class="qty"><button type="button" class="js-dec" aria-label="Moins">−</button><input type="number" inputmode="numeric" min="1" max="9999" value="1" class="js-qty" aria-label="Quantité"><button type="button" class="js-inc" aria-label="Plus">+</button></div>
      <button type="button" class="add js-add" data-id="{{ $r['id'] }}"><svg class="ic sm"><use href="#i-plus"/></svg>Ajouter au panier</button>
    @endif
    <button type="button" class="fav js-fav {{ $r['fav'] ? 'on' : '' }}" data-id="{{ $r['id'] }}" aria-label="Favori"><svg class="ic"><use href="#i-star"/></svg></button>
    <div class="incart" style="text-align:left" @if(!$r['in_cart']) hidden @endif>{{ $r['in_cart'] }} dans le panier</div>
  </div>
</div>

@if(!empty($r['description']))<p style="color:var(--ink2);margin:0 0 16px;white-space:pre-line">{{ $r['description'] }}</p>@endif
<dl class="dl">
  <dt>Référence</dt><dd class="mono">{{ $r['code'] }}</dd>
  @if($r['brand'])<dt>Marque</dt><dd>{{ $r['brand'] }}</dd>@endif
  @if($r['category'])<dt>Catégorie</dt><dd>{{ $r['category'] }}</dd>@endif
  @if(!empty($r['tecdoc']))<dt>Réf. TecDoc</dt><dd class="mono">{{ $r['tecdoc'] }}</dd>@endif
  @if(!empty($r['barcode']))<dt>Code-barres</dt><dd class="mono">{{ $r['barcode'] }}</dd>@endif
  @if($r['unit'])<dt>Unité</dt><dd>{{ $r['unit'] }}</dd>@endif
  @if(!empty($r['dimensions']))<dt>Dimensions</dt><dd>{{ $r['dimensions'] }}</dd>@endif
  @if(!empty($r['weight']))<dt>Poids</dt><dd>{{ rtrim(rtrim(number_format($r['weight'], 3, ',', ' '), '0'), ',') }} kg</dd>@endif
  @if(!empty($r['origin']))<dt>Origine</dt><dd>{{ $r['origin'] }}</dd>@endif
</dl>

@if(!empty($r['equivalents_rows']))
  <h3>Équivalents et alternatives</h3>
  <div class="parts">
    @foreach($r['equivalents_rows'] as $r)
      @php $noEq = true; @endphp
      @include('portal._part')
    @endforeach
  </div>
@endif
