@extends('portal.layout')
@section('title', 'Mot de passe oublié')
@section('body')
<div class="auth2">
  @include('portal._brandpanel')
  <main class="af"><div class="af-in">
    <h2>Mot de passe oublié</h2>
    @if($sent)
      <div class="okb" style="margin-top:18px">Demande enregistrée. Si cette adresse correspond à un compte, notre équipe vous communiquera un nouveau mot de passe provisoire très rapidement.</div>
    @else
      <p class="lead">Indiquez votre e-mail ou votre nom d'utilisateur. Notre équipe est prévenue et vous envoie un mot de passe provisoire.</p>
      @if($errors->any())<div class="err">{{ $errors->first() }}</div>@endif
      <form method="POST" action="{{ route('portal.forgot.post') }}">
        @csrf
        <div class="fld"><label for="email">E-mail ou nom d'utilisateur</label><input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus autocapitalize="none" spellcheck="false" maxlength="150"></div>
        <button class="btn" type="submit">Envoyer la demande</button>
      </form>
    @endif
    <div class="links"><a href="{{ route('portal.login') }}">Retour à la connexion</a></div>
  </div></main>
</div>
@endsection
