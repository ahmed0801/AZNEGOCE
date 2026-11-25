@extends('portal.layout')
@section('title', 'Mot de passe')
@section('body')
<div class="auth2">
  @include('portal._brandpanel')
  <main class="af"><div class="af-in">
    @if($forced)
      <h2>Bienvenue {{ $pUser->name }}</h2>
      <p class="lead">Pour sécuriser votre compte, choisissez votre mot de passe personnel.</p>
    @else
      <h2>Mon mot de passe</h2>
      <p class="lead">Choisissez un nouveau mot de passe.</p>
    @endif
    @if($errors->any())<div class="err">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('portal.password.update') }}">
      @csrf
      <div class="fld"><label for="current_password">{{ $forced ? 'Mot de passe provisoire reçu' : 'Mot de passe actuel' }}</label><input type="password" id="current_password" name="current_password" required autofocus autocomplete="current-password"></div>
      <div class="fld"><label for="password">Nouveau mot de passe</label><input type="password" id="password" name="password" required autocomplete="new-password"></div>
      <div class="rules">Au moins 8 caractères, avec une lettre et un chiffre.</div>
      <div class="fld"><label for="password_confirmation">Confirmer le nouveau mot de passe</label><input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"></div>
      <button class="btn" type="submit">Enregistrer</button>
    </form>
    @if(!$forced)<div class="links"><a href="{{ route($s->homeRoute()) }}">{{ $s->quotesOnly() ? 'Retour à mes demandes' : 'Retour au catalogue' }}</a></div>
    @else<div class="links"><form method="POST" action="{{ route('portal.logout') }}" style="display:inline">@csrf<button type="submit" class="link">Me déconnecter</button></form></div>@endif
  </div></main>
</div>
@endsection
