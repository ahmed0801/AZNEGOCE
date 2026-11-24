@if(count($rows))
<div class="parts">
  @foreach($rows as $r)
    @php $noEq = true; @endphp
    @include('portal._part')
  @endforeach
</div>
@else
<div class="sg-empty">Aucun équivalent disponible.</div>
@endif
