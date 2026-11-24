<div class="contact">
    @if($s->phone)<div>Téléphone : <a href="tel:{{ preg_replace('/[^0-9+]/', '', $s->phone) }}">{{ $s->phone }}</a></div>@endif
    @if($s->whatsapp)<div>WhatsApp : <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->whatsapp) }}" target="_blank" rel="noopener">{{ $s->whatsapp }}</a></div>@endif
    @if($s->email)<div><a href="mailto:{{ $s->email }}">{{ $s->email }}</a></div>@endif
    @if($s->opening_hours)<div>{{ $s->opening_hours }}</div>@endif
    @if($s->address || $s->city)<div>{{ $s->address }}{{ $s->address && $s->city ? ', ' : '' }}{{ $s->city }}</div>@endif
</div>
