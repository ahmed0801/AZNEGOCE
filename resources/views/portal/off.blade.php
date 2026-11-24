@extends('portal.layout')
@section('title', 'Portail indisponible')
@section('body')
<div class="auth2">
  @include('portal._brandpanel')
  <main class="af"><div class="af-in">
    <h2>Portail indisponible</h2>
    <p class="lead">{{ $message ?: 'Le portail est momentanément indisponible. Merci de votre patience.' }}</p>
  </div></main>
</div>
@endsection
