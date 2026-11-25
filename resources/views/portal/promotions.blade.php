@extends('portal.layout')
@section('title', 'Promotions')
@section('body')
@include('portal._nav')
<div class="page" style="max-width:980px">
  <div class="oh"><h1 class="ph">Promotions</h1><a class="btn2" href="{{ route('portal.catalog') }}">Voir le catalogue</a></div>
  @if(!$showPrices)
    <p class="mini">Les prix ne sont pas affichés pour votre compte.</p>
  @elseif(!count($groups))
    <div class="note info" style="margin-top:14px">Aucune promotion en cours pour le moment. Revenez bientôt.</div>
  @endif
  @if(count($groups) > 1)
    <nav class="pchips" aria-label="Promotions en cours">
      @foreach($groups as $g)<a href="#promo-{{ $g['id'] }}"><b>{{ $g['name'] }}</b><small>{{ count($g['rows']) }} article{{ count($g['rows']) > 1 ? 's' : '' }} · jusqu'au {{ \Carbon\Carbon::parse($g['ends'])->format('d/m') }}</small></a>@endforeach
    </nav>
  @endif
  @foreach($groups as $g)
    <section id="promo-{{ $g['id'] }}" style="margin-top:22px;scroll-margin-top:90px">
      <h2 style="font-size:1.1rem;margin:0 0 2px">{{ $g['name'] }}</h2>
      <p class="mini" style="margin:0 0 10px">Jusqu'au {{ \Carbon\Carbon::parse($g['ends'])->format('d/m/Y') }} · {{ count($g['rows']) }} article{{ count($g['rows']) > 1 ? 's' : '' }}</p>
      @if(!count($g['rows']))<div class="note info">Aucun article de cette promotion n'est proposé pour votre compte actuellement.</div>@endif
      <div class="ol">
        @foreach($g['rows'] as $r)
          @php $pm = $r['pm']; $st = $r['stock']; $better = $pm['applied_id'] && $pm['applied_id'] != $g['id'] && $pm['applied'] < $pm['price']; @endphp
          <a class="oi" href="{{ route('portal.catalog', ['q' => $r['code']]) }}" style="grid-template-columns:150px minmax(0,1fr) 120px 150px;text-decoration:none;color:inherit">
            <span class="on">{{ $r['code'] }}@if($r['brand'])<small>{{ $r['brand'] }}</small>@endif</span>
            <span>{{ $r['name'] }}@if($pm['remaining'] !== null)<small>Il en reste {{ $pm['remaining'] }} au prix promo</small>@endif @if($better)<small>Prix appliqué au panier : {{ number_format($pm['applied'], 2, ',', ' ') }} € HT (promotion « {{ $pm['applied_name'] }} »)</small>@endif</span>
            <span class="stk {{ $st['state'] }}" style="justify-self:start"><i></i>{{ $st['label'] ?: ($st['state'] === 'out' ? 'Rupture' : 'Disponible') }}</span>
            <span style="text-align:right"><b style="font-size:1.1rem">{{ number_format($pm['price'], 2, ',', ' ') }} € HT</b><small><s>{{ number_format($pm['list'], 2, ',', ' ') }}</s> <span class="tag promo">−{{ rtrim(rtrim(number_format($pm['discount'], 1, ',', ''), '0'), ',') }} %</span></small></span>
          </a>
        @endforeach
      </div>
      @if(!empty($g['hidden']))
        <div class="phid">
          <b>{{ count($g['hidden']) }} article{{ count($g['hidden']) > 1 ? 's' : '' }} de cette promotion non proposé{{ count($g['hidden']) > 1 ? 's' : '' }} pour votre compte</b>
          @foreach($g['hidden'] as $hd)<span><code>{{ $hd['code'] }}</code> {{ $hd['name'] }} — {{ $hd['why'] }}</span>@endforeach
        </div>
      @endif
    </section>
  @endforeach
</div>
@endsection
