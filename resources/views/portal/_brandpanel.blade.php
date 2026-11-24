<aside class="ab">
  <div class="logo">
    @if($s->logo_url)<img src="{{ $s->logo_url }}" alt="{{ $s->company_name }}">@else<span class="mark">{{ mb_strtoupper(mb_substr($s->company_name ?: 'P', 0, 1)) }}</span>@endif
    <b>{{ $s->company_name ?: 'Portail professionnel' }}</b>
  </div>
  <div>
    <h1>{{ $s->tagline ?: 'Vos pièces, au prix de votre compte.' }}</h1>
    @if($s->welcome_message)<p>{{ $s->welcome_message }}</p>@endif
    <ul>
      <li><i></i><span>Votre prix net affiché sur chaque référence</span></li>
      <li><i></i><span>Stock en direct, équivalences comprises</span></li>
      <li><i></i><span>Collez une liste de références, le panier est prêt</span></li>
    </ul>
  </div>
  <div class="ct">
    @if($s->phone)<div>Téléphone : <a href="tel:{{ preg_replace('/[^0-9+]/', '', $s->phone) }}">{{ $s->phone }}</a></div>@endif
    @if($s->whatsapp)<div>WhatsApp : <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->whatsapp) }}" target="_blank" rel="noopener">{{ $s->whatsapp }}</a></div>@endif
    @if($s->email)<div><a href="mailto:{{ $s->email }}">{{ $s->email }}</a></div>@endif
    @if($s->opening_hours)<div>{{ $s->opening_hours }}</div>@endif
  </div>
</aside>
