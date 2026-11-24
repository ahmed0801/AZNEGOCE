@extends('portal.layout')
@section('title', 'Mon compte')
@section('body')
@include('portal._nav')
@php $d = function ($x) { return $x ? \Carbon\Carbon::parse($x)->format('d/m/Y') : '—'; }; $m = function ($n) { return number_format($n, 2, ',', ' ') . ' €'; }; @endphp
<div class="page" style="max-width:980px">
  <h1 class="ph">Mon compte</h1>
  @include('portal._account_tabs')
  <div class="kpis">
    <div class="kpi"><span>Solde de votre compte</span><b>{{ $m($solde) }}</b><small>Factures émises, règlements déduits</small></div>
    <div class="kpi {{ $nLate ? 'bad' : '' }}"><span>Factures à régler</span><b>{{ $m($open) }}</b><small>{{ $nUnpaid }} facture{{ $nUnpaid > 1 ? 's' : '' }}@if($nLate), dont {{ $nLate }} en retard ({{ $m($late) }})@endif</small></div>
    <div class="kpi {{ $dnCount ? 'warn' : '' }}"><span>Livré, pas encore facturé</span><b>{{ $m($dnTotal) }}</b><small>{{ $dnCount }} bon{{ $dnCount > 1 ? 's' : '' }} de livraison ouvert{{ $dnCount > 1 ? 's' : '' }}</small></div>
    <div class="kpi"><span>Total en cours</span><b>{{ $m($exposure) }}</b><small>Solde + livraisons non facturées</small></div>
  </div>

  <div class="sec-h"><h2>Dernières factures</h2><a href="{{ route('portal.account.invoices') }}">Tout voir</a></div>
  @if($invs->isEmpty())<div class="empty"><b>Aucune facture</b>Vos factures apparaîtront ici.</div>@else
  <div class="parts"><table class="ot2"><thead><tr><th>Facture</th><th>Date</th><th>Échéance</th><th class="r">Total TTC</th><th class="r">Reste à payer</th><th></th></tr></thead><tbody>
    @foreach($invs as $i)
      <tr class="clk" onclick="location.href='{{ route('portal.account.invoice', ['id' => $i->id]) }}'"><td><a class="mono" href="{{ route('portal.account.invoice', ['id' => $i->id]) }}">{{ $i->numdoc }}</a></td><td>{{ $d($i->invoice_date) }}</td><td>{{ $d($i->due_date) }}@if($i->late) <span class="tag bad">en retard</span>@endif</td>
        <td class="r">{{ $m($i->total_ttc) }}</td><td class="r">@if($i->rest > 0)<b>{{ $m($i->rest) }}</b>@else<span class="tag ok">Payée</span>@endif</td>
        <td class="r"><a class="pdfb" href="{{ route('portal.account.invoice.pdf', ['id' => $i->id]) }}" onclick="event.stopPropagation()" title="Télécharger la facture"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>PDF</a></td></tr>
    @endforeach</tbody></table></div>
  @endif

  <div class="sec-h"><h2>Bons de livraison non facturés</h2><a href="{{ route('portal.account.dns') }}">Tous les bons</a></div>
  @if($dns->isEmpty())<div class="empty"><b>Rien en attente de facturation</b>Toutes vos livraisons sont facturées.</div>@else
  <div class="parts"><table class="ot2"><thead><tr><th>Bon de livraison</th><th>Date</th><th class="r">Total TTC</th><th></th></tr></thead><tbody>
    @foreach($dns as $b)
      <tr class="clk" onclick="location.href='{{ route('portal.account.dn', ['id' => $b->id]) }}'"><td><a class="mono" href="{{ route('portal.account.dn', ['id' => $b->id]) }}">{{ $b->numdoc }}</a></td><td>{{ $d($b->delivery_date) }}</td><td class="r"><b>{{ $m($b->total_ttc) }}</b></td><td class="r"><a class="pdfb" href="{{ route('portal.account.dn.pdf', ['id' => $b->id]) }}" onclick="event.stopPropagation()" title="Télécharger le bon de livraison"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>PDF</a></td></tr>
    @endforeach</tbody></table></div>
  @endif
  <p class="mini" style="margin-top:14px">Ces informations reflètent l'état de votre compte à l'instant présent. Pour toute question, contactez-nous{{ $s->phone ? ' au ' . $s->phone : '' }}.</p>
</div>
@endsection
