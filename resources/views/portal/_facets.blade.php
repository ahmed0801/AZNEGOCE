@php
  $mk = function ($key, $title, $list, $sel) { return [$key, $title, $list, $sel]; };
  $groups = [['brand', 'Marque', $facets['brands'] ?? []], ['category', 'Famille', $facets['categories'] ?? []]];
@endphp
@foreach($groups as $g)
  @if(count($g[2]))
  <section class="fs" data-key="{{ $g[0] }}">
    <h3>{{ $g[1] }}</h3>
    @if(count($g[2]) > 8)<input type="search" class="fsearch js-fsearch" placeholder="Filtrer…" aria-label="Filtrer {{ mb_strtolower($g[1]) }}">@endif
    <div class="fl">
    @foreach($g[2] as $i => $o)
      <label class="fopt {{ $i >= 8 && !$o['on'] ? 'extra' : '' }}" @if($i >= 8 && !$o['on']) hidden @endif data-name="{{ mb_strtolower($o['name']) }}">
        <input type="checkbox" name="{{ $g[0] }}[]" value="{{ $o['id'] }}" {{ $o['on'] ? 'checked' : '' }}><span>{{ $o['name'] }}</span><small>{{ $o['n'] }}</small>
      </label>
    @endforeach
    </div>
    @if(count($g[2]) > 8)<button type="button" class="more js-more">Voir tout ({{ count($g[2]) }})</button>@endif
  </section>
  @endif
@endforeach
@if(!empty($facets['subs']))
  <section class="fs" data-key="sub">
    <h3>Sous-famille</h3>
    <div class="fl">
    @foreach($facets['subs'] as $o)
      <label class="fopt" data-name="{{ mb_strtolower($o['name']) }}">
        <input type="checkbox" name="sub[]" value="{{ $o['id'] }}" {{ $o['on'] ? 'checked' : '' }}><span>{{ $o['name'] }}</span><small>{{ $o['n'] }}</small>
      </label>
    @endforeach
    </div>
  </section>
@endif
<section class="fs">
  <label class="fopt"><input type="checkbox" name="stock" value="1" {{ !empty($filters['stock']) ? 'checked' : '' }}><span>En stock uniquement</span></label>
  <label class="fopt"><input type="checkbox" name="fav" value="1" {{ !empty($filters['fav']) ? 'checked' : '' }}><span>Mes favoris</span><small>{{ $favCount }}</small></label>
</section>
