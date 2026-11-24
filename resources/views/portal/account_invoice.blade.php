@extends('portal.layout')
@section('title', 'Facture ' . $inv->numdoc)
@section('body')
@include('portal._nav')
@php $d = function ($x) { return $x ? \Carbon\Carbon::parse($x)->format('d/m/Y') : '—'; }; $m = function ($n) { return number_format($n, 2, ',', ' ') . ' €'; }; @endphp
<div class="page" style="max-width:980px">
  <a href="{{ route('portal.account.invoices') }}" class="mini" style="text-decoration:none">Retour aux factures</a>
  <div class="oh">
    <div><h1 class="ph" style="margin:6px 0 2px">Facture {{ $inv->numdoc }}</h1><span class="mini">Émise le {{ $d($inv->invoice_date) }}</span></div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap"><a class="btn2 pri" href="{{ route('portal.account.invoice.pdf', ['id' => $inv->id]) }}"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>Télécharger la facture (PDF)</a><a class="btn2" target="_blank" rel="noopener" href="{{ route('portal.account.invoice.pdf', ['id' => $inv->id, 'inline' => 1]) }}">Afficher</a>
    @if($inv->rest > 0)<span class="os st-{{ $inv->late ? 'refused' : 'pending' }}" style="font-size:.95rem;padding:7px 16px">{{ $inv->late ? 'En retard' : 'À régler' }}</span>@else<span class="os st-validated" style="font-size:.95rem;padding:7px 16px">Payée</span>@endif</div>
  </div>
  <div class="ocard"><dl class="dl" style="margin:0">
    <dt>Échéance</dt><dd>{{ $d($inv->due_date) }}</dd>
    <dt>Reste à payer</dt><dd><b>{{ $m($inv->rest) }}</b></dd>
    @if($bls->count())<dt>Bons de livraison</dt><dd>@foreach($bls as $b)<a class="mono" href="{{ route('portal.account.dn', ['id' => $b->id]) }}">{{ $b->numdoc }}</a>{{ $loop->last ? '' : ', ' }}@endforeach</dd>@endif
  </dl></div>
  <div class="parts" style="margin-top:14px"><table class="ot2"><thead><tr><th>Référence</th><th>Désignation</th><th class="r">Qté</th><th class="r">Prix HT</th><th class="r">Total HT</th></tr></thead><tbody>
    @foreach($lines as $l)
      <tr><td><span class="plate" style="font-size:.8rem;padding:3px 8px;cursor:text">{{ $l->article_code }}</span></td><td>{{ $names[$l->article_code] ?? '' }}</td><td class="r">{{ $l->quantity }}</td>
        <td class="r">{{ number_format($l->unit_price_ht, 2, ',', ' ') }} €@if($l->remise > 0)<small class="mini" style="display:block">−{{ $l->remise + 0 }} %</small>@endif</td><td class="r"><b>{{ $m($l->total_ligne_ht) }}</b></td></tr>
    @endforeach</tbody></table></div>
  <div class="tot" style="max-width:340px;margin:16px 0 0 auto">
    <div><span>Total HT</span><b>{{ $m($inv->total_ht) }}</b></div>
    <div><span>TVA</span><span>{{ $m($inv->total_ttc - $inv->total_ht) }}</span></div>
    <div class="ttl"><span>Total TTC</span><span>{{ $m($inv->total_ttc) }}</span></div>
  </div>
</div>
@endsection
