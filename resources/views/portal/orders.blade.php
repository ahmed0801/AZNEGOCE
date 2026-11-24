@extends('portal.layout')
@section('title', 'Mes commandes')
@section('body')
@include('portal._nav')
<div class="page" style="max-width:980px">
  @if(session('status'))<div class="notes"><div class="note info">{{ session('status') }}</div></div>@endif
  <h1 class="ph">Mes commandes</h1>
  @if($orders->total() == 0)
    <div class="empty"><b>Aucune commande pour l'instant</b>Vos commandes passées depuis le portail apparaîtront ici.
      <div class="acts"><a class="btn2 pri" href="{{ route('portal.catalog') }}">Ouvrir le catalogue</a></div></div>
  @else
    <div class="ol">
      @foreach($orders as $o)
        <a class="oi" href="{{ route('portal.order', ['order' => $o->id]) }}">
          <span class="on">{{ $o->number }}</span>
          <span class="od">{{ $o->created_at->format('d/m/Y à H:i') }}<small>{{ $o->lines_count }} référence{{ $o->lines_count > 1 ? 's' : '' }}, {{ $o->units }} pièce{{ $o->units > 1 ? 's' : '' }}</small></span>
          <span class="ot">{{ number_format($o->total_ttc, 2, ',', ' ') }} €<small>TTC</small></span>
          <span class="os st-{{ $o->status }}{{ $o->modified ? ' mod' : '' }}">{{ $o->status_label }}</span>
        </a>
      @endforeach
    </div>
    <div class="pager">
      @if($orders->currentPage() > 1)<a class="btn2" href="{{ $orders->previousPageUrl() }}">Précédent</a>@endif
      <span>Page {{ $orders->currentPage() }} sur {{ $orders->lastPage() }}</span>
      @if($orders->hasMorePages())<a class="btn2" href="{{ $orders->nextPageUrl() }}">Suivant</a>@endif
    </div>
  @endif
</div>
@endsection
