@extends('portal.layout')
@section('title', 'Connexion')
@section('body')
<div class="auth2">
  @include('portal._brandpanel')
  <main class="af"><div class="af-in">
    <h2>Connexion</h2>
    <p class="lead">Accédez à votre catalogue et à vos prix.</p>
    @if(session('status'))<div class="okb">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="err">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('portal.login.post') }}" autocomplete="on">
      @csrf
      <div class="fld"><label for="email">E-mail ou nom d'utilisateur</label><input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" autocapitalize="none" spellcheck="false" maxlength="150"></div>
      <div class="fld"><label for="password">Mot de passe</label>
        <div class="pw"><input type="password" id="password" name="password" required autocomplete="current-password"><button type="button" id="togglePw">Afficher</button></div></div>
      <button class="btn" type="submit">Se connecter</button>
    </form>
    <div class="links"><a href="{{ route('portal.forgot') }}">Mot de passe oublié ?</a></div>
  </div></main>
</div>
<script>
document.getElementById('togglePw').addEventListener('click',function(){var i=document.getElementById('password');var s=i.type==='password';i.type=s?'text':'password';this.textContent=s?'Masquer':'Afficher';});
</script>
@endsection
