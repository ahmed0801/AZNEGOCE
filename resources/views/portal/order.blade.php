@extends('portal.layout')
@section('title', 'Commande ' . $o->number)
@section('body')
@include('portal._nav')
<div class="page" style="max-width:980px">
  @if(session('status'))<div class="notes"><div class="note info">{{ session('status') }}</div></div>@endif
  <a href="{{ route('portal.orders') }}" class="mini" style="text-decoration:none">Retour à mes commandes</a>
  <div class="oh">
    <div><h1 class="ph" style="margin:6px 0 2px">{{ $o->number }}</h1><span class="mini">Passée le {{ $o->created_at->format('d/m/Y à H:i') }}</span></div>
    <span class="os st-{{ $o->status }}{{ $o->modified ? ' mod' : '' }}" style="font-size:.95rem;padding:7px 16px">{{ $o->status_label }}</span>
  </div>

  @php
    $okFinance = $cs->show_finance; $closed = in_array($o->status, ['refused', 'cancelled'], true); $acc = $o->status === 'validated';
    $dt = function ($x) { return $x ? \Carbon\Carbon::parse($x)->format('d/m/Y') : ''; };
  @endphp
  <div class="tl">
    <div class="st done"><b>Commande reçue</b><small>{{ $o->created_at->format('d/m/Y H:i') }}</small></div>
    <div class="st {{ $acc ? 'done' . ($o->modified ? ' mod' : '') : ($closed ? 'bad' : 'now') }}"><b>@if($acc){{ $o->modified ? 'Acceptée avec modifications' : 'Acceptée' }}@elseif($o->status === 'refused')Refusée@elseif($o->status === 'cancelled')Annulée@else En attente de validation @endif</b><small>{{ $acc && $o->validated_at ? $o->validated_at->format('d/m/Y H:i') : '' }}</small></div>
    <div class="st {{ $bl ? 'done' : ($acc ? 'now' : '') }}"><b>Bon de livraison</b><small>@if($bl)@if($okFinance)<a href="{{ route('portal.account.dn', ['id' => $bl->id]) }}">{{ $bl->numdoc }}</a>@else{{ $bl->numdoc }}@endif {{ $dt($bl->delivery_date) }}@elseif($acc)En préparation@else — @endif</small></div>
    <div class="st {{ $inv ? 'done' : '' }}"><b>Facture</b><small>@if($inv)@if($okFinance)<a href="{{ route('portal.account.invoice', ['id' => $inv->id]) }}">{{ $inv->numdoc }}</a>@else{{ $inv->numdoc }}@endif {{ $dt($inv->invoice_date) }}@else — @endif</small></div>
  </div>

  @if($o->status === 'pending')<div class="note info" style="margin:14px 0">Votre commande a bien été reçue. Notre équipe la valide très rapidement{{ $s->opening_hours ? ' (' . $s->opening_hours . ')' : '' }}.</div>@endif
  @if($acc && !$o->modified)<div class="note info" style="margin:14px 0;background:var(--okbg);border-color:transparent;color:var(--ok)">Commande acceptée{{ $o->auto_validated ? ' automatiquement' : '' }} sans modification.</div>@endif
  @if($acc && $o->modified)
    <div class="chg"><div class="note warn"><b>Votre commande a été acceptée avec des modifications.</b> Le détail des changements est indiqué ligne par ligne ci-dessous.@if($o->orig_total_ttc !== null) Total initial {{ number_format($o->orig_total_ttc, 2, ',', ' ') }} € TTC, total accepté <b>{{ number_format($o->total_ttc, 2, ',', ' ') }} € TTC</b>.@endif</div>
    @if($o->vendor_message)<div class="note info"><b>Message de notre équipe :</b> {{ $o->vendor_message }}</div>@endif</div>
  @elseif($acc && $o->vendor_message)<div class="note info" style="margin:14px 0"><b>Message de notre équipe :</b> {{ $o->vendor_message }}</div>@endif
  @if($o->status === 'refused')<div class="note bad" style="margin:14px 0"><b>Commande refusée.</b> {{ $o->refuse_reason }}@if($s->phone) Contactez-nous au {{ $s->phone }}.@endif</div>@endif

  <div class="ocard">
    <dl class="dl" style="margin:0">
      <dt>Réception</dt><dd>{{ $o->delivery_mode === 'pickup' ? 'Retrait au magasin' : 'Livraison' }}</dd>
      @if($o->po_ref)<dt>Votre référence</dt><dd>{{ $o->po_ref }}</dd>@endif
      @if($o->note)<dt>Votre message</dt><dd style="white-space:pre-line">{{ $o->note }}</dd>@endif
      <dt>Commandé par</dt><dd>{{ optional($o->user)->name ?: '—' }}</dd>
    </dl>
  </div>

  <div class="parts" style="margin-top:14px"><table class="ot2">
    <thead><tr><th>Référence</th><th>Désignation</th><th class="r">Qté</th><th class="r">Prix HT</th><th class="r">Total HT</th></tr></thead>
    <tbody>
    @foreach($o->lines as $l)
      @php $qd = $l->orig_qty !== null && $l->orig_qty != $l->qty; $pd = $l->orig_unit_ht !== null && abs($l->unit_ht - $l->orig_unit_ht) > 0.00005; @endphp
      <tr class="{{ $l->removed ? 'gone' : '' }}"><td><span class="plate" style="font-size:.8rem;padding:3px 8px;cursor:text">{{ $l->code }}</span>@if($l->brand)<div class="brand" style="margin-top:3px">{{ $l->brand }}</div>@endif</td>
        <td>{{ $l->name }}@if($l->removed)<span class="diff rm">Retiré de la commande</span>@endif</td>
        <td class="r">@if($qd && !$l->removed)<span class="old">{{ $l->orig_qty }}</span>@endif{{ $l->removed ? ($l->orig_qty ?? $l->qty) : $l->qty }}@if($qd && !$l->removed)<span class="diff">Quantité modifiée</span>@endif</td>
        <td class="r">@if($pd && !$l->removed)<span class="old">{{ number_format($l->orig_unit_ht, 2, ',', ' ') }} €</span>@endif{{ number_format($l->unit_ht, 2, ',', ' ') }} €@if($pd && !$l->removed)<span class="diff">Prix modifié</span>@endif</td>
        <td class="r"><b>{{ $l->removed ? '—' : number_format($l->line_ht, 2, ',', ' ') . ' €' }}</b></td></tr>
    @endforeach
    </tbody></table></div>
  <div class="tot" style="max-width:340px;margin:16px 0 0 auto">
    <div><span>Total HT</span><b>{{ number_format($o->total_ht, 2, ',', ' ') }} €</b></div>
    <div><span>TVA</span><span>{{ number_format($o->total_tva, 2, ',', ' ') }} €</span></div>
    <div class="ttl"><span>Total TTC</span><span>{{ number_format($o->total_ttc, 2, ',', ' ') }} €</span></div>
  </div>
  <p class="mini" style="text-align:right;margin:4px 0 0">{{ $o->modified ? 'Prix et quantités après acceptation par notre équipe.' : 'Prix de la commande figés au moment de l\'envoi.' }}</p>

  <div class="acts" style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px">
    @if($cs->can_order && !$customer->blocked)<button type="button" class="btn2 pri" id="reorder"><svg class="ic sm"><use href="#i-cart"/></svg>Commander à nouveau</button>@endif
    @if($o->status === 'pending')
      <form method="POST" action="{{ route('portal.order.cancel', ['order' => $o->id]) }}" onsubmit="return confirm('Annuler cette commande ?')">@csrf<button type="submit" class="btn2">Annuler la commande</button></form>
    @endif
  </div>
</div>
<script>
(function(){var b=document.getElementById('reorder');if(!b)return;
b.addEventListener('click',function(){b.disabled=true;
fetch({!! json_encode(route('portal.order.reorder', ['order' => $o->id], false)) !!},{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'X-Requested-With':'XMLHttpRequest'}})
.then(function(r){return r.json()}).then(function(j){if(j.ok){location.href={!! json_encode(route('portal.catalog', [], false)) !!}+'?cart=1';}else{b.disabled=false;alert(j.message||'Impossible pour le moment.')}}).catch(function(){b.disabled=false});});})();
</script>
@endsection
