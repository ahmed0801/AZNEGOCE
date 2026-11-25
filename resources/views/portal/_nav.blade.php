<header class="hd"><div class="hd-in" style="grid-template-columns:auto minmax(0,1fr) auto">
  <a class="logo" href="{{ route($s->homeRoute()) }}">
    @if($s->logo_url)<img src="{{ $s->logo_url }}" alt="">@else<span class="mark">{{ mb_strtoupper(mb_substr($s->company_name ?: 'P', 0, 1)) }}</span>@endif
    <b>{{ $s->company_name ?: 'Portail professionnel' }}</b>
  </a>
  <nav class="nav2">@if(!$s->quotesOnly())<a href="{{ route('portal.catalog') }}">Catalogue</a><a href="{{ route('portal.orders') }}" class="{{ request()->routeIs('portal.order*') ? 'on' : '' }}">Mes commandes</a>@if($s->show_prices && $cs->show_prices)@php $pnN = \App\Services\PortalPromoNotices::for($pUser->customer, $s)['count']; @endphp<a href="{{ route('portal.promotions') }}" class="{{ request()->routeIs('portal.promotions') ? 'on' : '' }}">Promotions @if($pnN)<span class="nb">{{ $pnN }}</span>@endif</a>@endif
  @endif
  @php $qn = \App\Services\PortalQuoteNotices::for($pUser->customer_id)['count']; @endphp<a href="{{ route('portal.quotes') }}" class="{{ request()->routeIs('portal.quote*') ? 'on' : '' }}">Mes devis @if($qn)<span class="nb">{{ $qn }}</span>@endif</a>@if(!$s->quotesOnly() && $cs->show_finance)<a href="{{ route('portal.account') }}" class="{{ request()->routeIs('portal.account*') ? 'on' : '' }}">Mon compte</a>@endif</nav>
  <div class="tools"><div class="me">
    <button type="button" class="tbtn" id="meBtn" aria-label="Mon compte"><span class="av">{{ mb_strtoupper(mb_substr($pUser->name, 0, 1)) }}</span></button>
    <div class="menu" id="menu">
      <div class="who"><b>{{ $pUser->name }}</b><span>{{ $customer->name }}</span></div>
      <a href="{{ route('portal.password') }}">Changer mon mot de passe</a>
      <form method="POST" action="{{ route('portal.logout') }}">@csrf<button type="submit">Se déconnecter</button></form>
    </div></div></div>
</div></header>
<script>document.getElementById('meBtn').addEventListener('click',function(e){e.stopPropagation();document.getElementById('menu').classList.toggle('open')});document.addEventListener('click',function(){document.getElementById('menu').classList.remove('open')});</script>
