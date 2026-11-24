@extends('portal.layout')
@section('title', 'Mes bons de livraison')
@section('body')
@include('portal._nav')
@php $d = function ($x) { return $x ? \Carbon\Carbon::parse($x)->format('d/m/Y') : '—'; }; $m = function ($n) { return number_format($n, 2, ',', ' ') . ' €'; }; @endphp
<div class="page" style="max-width:980px">
  <h1 class="ph">Mon compte</h1>
  @include('portal._account_tabs')
  <div class="note {{ $openCount ? 'warn' : 'info' }}" style="margin-bottom:14px">Livré mais pas encore facturé : <b>{{ $m($openTotal) }}</b> TTC sur {{ $openCount }} bon{{ $openCount > 1 ? 's' : '' }} de livraison.</div>
  <div class="chips"><a class="{{ $f === 'all' ? 'on' : '' }}" href="{{ route('portal.account.dns') }}">Tous</a><a class="{{ $f === 'open' ? 'on' : '' }}" href="{{ route('portal.account.dns', ['f' => 'open']) }}">Non facturés</a></div>
  @if($rows->total() == 0)<div class="empty"><b>Aucun bon de livraison</b>Vos bons de livraison apparaîtront ici.</div>@else
  <div class="parts"><table class="ot2"><thead><tr><th>Bon de livraison</th><th>Date</th><th>Facturation</th><th class="r">Total TTC</th><th class="r"></th></tr></thead><tbody>
    @foreach($rows as $b)
      <tr class="clk" onclick="location.href='{{ route('portal.account.dn', ['id' => $b->id]) }}'"><td><a class="mono" href="{{ route('portal.account.dn', ['id' => $b->id]) }}">{{ $b->numdoc }}</a></td><td>{{ $d($b->delivery_date) }}</td>
        <td>@if($b->invoiced)<span class="tag ok">Facturé</span>@else<span class="tag warn">Non facturé</span>@endif</td><td class="r"><b>{{ $m($b->total_ttc) }}</b></td><td class="r"><a class="pdfb" href="{{ route('portal.account.dn.pdf', ['id' => $b->id]) }}" onclick="event.stopPropagation()" title="Télécharger le bon de livraison"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>PDF</a></td></tr>
    @endforeach</tbody></table></div>
  <div class="pager">
    @if($rows->currentPage() > 1)<a class="btn2" href="{{ $rows->previousPageUrl() }}">Précédent</a>@endif
    <span>Page {{ $rows->currentPage() }} sur {{ $rows->lastPage() }}</span>
    @if($rows->hasMorePages())<a class="btn2" href="{{ $rows->nextPageUrl() }}">Suivant</a>@endif
  </div>
  @endif
</div>
@endsection
