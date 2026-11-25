@extends('portal.layout')
@section('title', 'Mes demandes de devis')
@section('body')
@include('portal._nav')
<div class="page" style="max-width:980px">
  @if(session('status'))<div class="notes"><div class="note info">{{ session('status') }}</div></div>@endif
  <div class="oh"><h1 class="ph">Mes demandes de devis</h1><a class="btn2 pri" href="{{ route('portal.quote.new') }}">Nouvelle demande</a></div>
  @if(count($erpQuotes))
    <h2 style="font-size:1.05rem;margin:18px 0 4px">Devis établis par notre équipe</h2>
    <div class="ol">
      @foreach($erpQuotes as $e)
        <div class="oi" style="cursor:default">
          <span class="on">{{ $e->numdoc }}</span>
          <span class="od">{{ \Carbon\Carbon::parse($e->order_date)->format('d/m/Y') }}@if(!in_array($e->id, $erpSeen))<small><b style="color:var(--brand-ink)">Nouveau</b></small>@endif</span>
          <span class="ot">{{ number_format($e->total_ttc, 2, ',', ' ') }} €<small>TTC</small></span>
          <span><a class="pdfb" href="{{ route('portal.quote.erp.pdf', ['id' => $e->id]) }}"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>PDF</a></span>
        </div>
      @endforeach
    </div>
    <h2 style="font-size:1.05rem;margin:22px 0 4px">Mes demandes</h2>
  @endif
  @if($quotes->total() == 0 && !count($erpQuotes))
    <div class="empty" style="margin-top:14px"><b>Aucune demande pour l'instant</b>Une pièce introuvable, ou plusieurs à chiffrer pour un véhicule ? Envoyez-nous la liste, nous vous répondons avec prix et disponibilité.
      <div class="acts"><a class="btn2 pri" href="{{ route('portal.quote.new') }}">Faire une demande de devis</a></div></div>
  @elseif($quotes->total() > 0)
    <div class="ol" style="margin-top:14px">
      @foreach($quotes as $q)
        <a class="oi" href="{{ route('portal.quote', ['id' => $q->id]) }}">
          <span class="on">{{ $q->number }}</span>
          <span class="od">{{ $q->created_at->format('d/m/Y à H:i') }}<small>{{ $q->vehicle_text ?: 'Sans véhicule précisé' }} · {{ $q->lines_count }} pièce{{ $q->lines_count > 1 ? 's' : '' }}</small></span>
          <span class="ot">@if($q->status === 'answered' || $q->status === 'accepted'){{ number_format($q->total_ttc, 2, ',', ' ') }} €<small>TTC</small>@else<small>—</small>@endif</span>
          <span class="os st-{{ $q->status }}">{{ $q->status_label }}</span>
        </a>
      @endforeach
    </div>
    <div class="pager">
      @if($quotes->currentPage() > 1)<a class="btn2" href="{{ $quotes->previousPageUrl() }}">Précédent</a>@endif
      <span>Page {{ $quotes->currentPage() }} sur {{ $quotes->lastPage() }}</span>
      @if($quotes->hasMorePages())<a class="btn2" href="{{ $quotes->nextPageUrl() }}">Suivant</a>@endif
    </div>
  @endif
</div>
@endsection
