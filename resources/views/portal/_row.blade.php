<div class="row" data-id="{{ $r['id'] }}">
  <div class="info">
    <div class="ref">{{ $r['code'] }}</div>
    <div class="nm2">{{ $r['name'] }}</div>
    <div class="meta">
      @if($r['brand'])<span class="br">{{ $r['brand'] }}</span>@endif
      @if($r['category'])<span>{{ $r['category'] }}</span>@endif
      @if(count($r['alt_refs']))<span class="alt">Réf. {{ implode(' · ', $r['alt_refs']) }}</span>@endif
      @if($r['unit'])<span>Unité : {{ $r['unit'] }}</span>@endif
    </div>
  </div>
  <div class="st">
    @if(!$r['stock']['hidden'])
      <span class="stock {{ $r['stock']['state'] }}"><i></i>{{ $r['stock']['label'] }}</span>
    @elseif($r['stock']['state'] === 'out')
      <span class="stock out"><i></i>Rupture</span>
    @endif
  </div>
  <div class="price">
    @if($r['price'])
      <div class="p">{{ number_format($r['price']['net_ht'], 2, ',', ' ') }} €<small>HT</small></div>
      <div class="ttc">{{ number_format($r['price']['net_ttc'], 2, ',', ' ') }} € TTC</div>
      @if($r['price']['prix_net'])
        <div class="old"><span class="badge net">Prix net</span></div>
      @elseif($r['price']['discount'] > 0)
        <div class="old"><s>{{ number_format($r['price']['list_ht'], 2, ',', ' ') }} €</s><span class="badge off">−{{ rtrim(rtrim(number_format($r['price']['discount'], 1, ',', ''), '0'), ',') }} %</span></div>
      @endif
    @else
      <div class="ask">Prix sur demande @if($s->phone)<br><a href="tel:{{ preg_replace('/[^0-9+]/', '', $s->phone) }}">{{ $s->phone }}</a>@endif</div>
    @endif
  </div>
  @if($r['equivalents'] > 0 && empty($noEq))
  <div class="eqbar"><button type="button" class="btn-eq" data-eq="{{ $r['id'] }}">⇄ {{ $r['equivalents'] }} équivalent{{ $r['equivalents'] > 1 ? 's' : '' }}</button>@if($r['stock']['state'] === 'out')<span style="font-size:.8rem;color:var(--warn)">Cet article est en rupture : voyez les équivalents.</span>@endif</div>
  <div class="eqbox" id="eq-{{ $r['id'] }}" hidden></div>
  @endif
</div>
