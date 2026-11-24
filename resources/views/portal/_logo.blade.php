<div class="lg">
    @if($s->logo_url)<img src="{{ $s->logo_url }}" alt="{{ $s->company_name }}">@else<div class="logo-ini">{{ mb_strtoupper(mb_substr($s->company_name ?: 'P', 0, 1)) }}</div>@endif
    <h1>{{ $s->company_name ?: 'Portail professionnel' }}</h1>
    @if($s->tagline)<div class="tag">{{ $s->tagline }}</div>@endif
</div>
