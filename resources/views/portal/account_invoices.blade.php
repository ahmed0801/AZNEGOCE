@extends('portal.layout')
@section('title', 'Mes factures')
@section('body')
@include('portal._nav')
@php $d = function ($x) { return $x ? \Carbon\Carbon::parse($x)->format('d/m/Y') : '—'; }; $m = function ($n) { return number_format($n, 2, ',', ' ') . ' €'; }; @endphp
<div class="page" style="max-width:980px">
  <h1 class="ph">Mon compte</h1>
  @include('portal._account_tabs')
  <div class="chips"><a class="{{ $f === 'all' ? 'on' : '' }}" href="{{ route('portal.account.invoices') }}">Toutes</a><a class="{{ $f === 'unpaid' ? 'on' : '' }}" href="{{ route('portal.account.invoices', ['f' => 'unpaid']) }}">À régler</a></div>
  @if($rows->total() == 0)<div class="empty"><b>Aucune facture</b>{{ $f === 'unpaid' ? 'Tout est réglé.' : 'Vos factures apparaîtront ici.' }}</div>@else
  <div class="parts"><table class="ot2"><thead><tr><th>Facture</th><th>Date</th><th>Échéance</th><th class="r">Total TTC</th><th class="r">Reste à payer</th><th></th></tr></thead><tbody>
    @foreach($rows as $i)
      <tr class="clk" onclick="location.href='{{ route('portal.account.invoice', ['id' => $i->id]) }}'"><td><a class="mono" href="{{ route('portal.account.invoice', ['id' => $i->id]) }}">{{ $i->numdoc }}</a></td><td>{{ $d($i->invoice_date) }}</td><td>{{ $d($i->due_date) }}@if($i->late) <span class="tag bad">en retard</span>@endif</td>
        <td class="r">{{ $m($i->total_ttc) }}</td><td class="r">@if($i->rest > 0)<b>{{ $m($i->rest) }}</b>@else<span class="tag ok">Payée</span>@endif</td>
        <td class="r"><a class="pdfb" href="{{ route('portal.account.invoice.pdf', ['id' => $i->id]) }}" onclick="event.stopPropagation()" title="Télécharger la facture"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>PDF</a></td></tr>
    @endforeach</tbody></table></div>
  <div class="pager">
    @if($rows->currentPage() > 1)<a class="btn2" href="{{ $rows->previousPageUrl() }}">Précédent</a>@endif
    <span>Page {{ $rows->currentPage() }} sur {{ $rows->lastPage() }}</span>
    @if($rows->hasMorePages())<a class="btn2" href="{{ $rows->nextPageUrl() }}">Suivant</a>@endif
  </div>
  @endif
</div>
@endsection
