<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEGOCIX - Connexion</title>
    <style>
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.25rem;
            font-family:'Public Sans','Segoe UI',system-ui,-apple-system,Arial,sans-serif;color:#0f172a;
            background:#0b1f44 url('{{ asset('assets/img/artpg.png') }}') center/cover no-repeat fixed;
            position:relative;
        }
        /* voile sombre pour la lisibilité */
        body::before{content:"";position:fixed;inset:0;background:linear-gradient(135deg,rgba(7,22,52,.82),rgba(14,48,110,.62) 55%,rgba(7,22,52,.78));z-index:0}

        .login-card{
            position:relative;z-index:1;width:100%;max-width:410px;
            background:rgba(255,255,255,.94);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
            border:1px solid rgba(255,255,255,.6);border-radius:22px;padding:2rem 2rem 1.4rem;
            box-shadow:0 25px 60px -12px rgba(0,0,0,.55);
            animation:rise .5s ease both;
        }
        @keyframes rise{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}

        .logo{display:block;margin:0 auto .9rem;max-width:170px;max-height:90px;width:auto;height:auto}
        .welcome{text-align:center;margin-bottom:1.4rem}
        .welcome h1{margin:0;font-size:1.4rem;font-weight:800;letter-spacing:-.3px;color:#0b1f44}
        .welcome p{margin:.3rem 0 0;font-size:.88rem;color:#64748b}

        .alert{display:flex;gap:.6rem;align-items:flex-start;background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;color:#991b1b;border-radius:10px;padding:.7rem .85rem;margin-bottom:1rem;font-size:.85rem;line-height:1.4}
        .alert svg{flex:0 0 18px;margin-top:1px}
        .alert ul{margin:0;padding:0;list-style:none}

        .field{margin-bottom:1rem}
        .field label{display:block;font-size:.8rem;font-weight:700;color:#334155;margin-bottom:.35rem}
        .input-wrap{position:relative}
        .input-wrap .ico{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;display:flex}
        .input-wrap input{
            width:100%;height:46px;padding:0 2.8rem 0 2.6rem;font-size:.95rem;font-family:inherit;color:#0f172a;
            background:#f8fafc;border:1.5px solid #dbe3ee;border-radius:12px;outline:none;transition:border-color .15s,box-shadow .15s,background .15s;
        }
        .input-wrap input:focus{background:#fff;border-color:#2563eb;box-shadow:0 0 0 4px rgba(37,99,235,.15)}
        .input-wrap input:focus ~ .ico,.input-wrap:focus-within .ico{color:#2563eb}
        .toggle-pw{position:absolute;right:.35rem;top:50%;transform:translateY(-50%);width:38px;height:38px;border:0;background:transparent;border-radius:10px;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center}
        .toggle-pw:hover{background:#eef2f7;color:#0b1f44}
        .caps{display:none;margin-top:.4rem;font-size:.76rem;color:#b45309;font-weight:600}
        .caps.on{display:block}

        .btn-login{
            width:100%;height:48px;margin-top:.4rem;border:0;border-radius:12px;cursor:pointer;
            font-family:inherit;font-size:.98rem;font-weight:700;color:#fff;letter-spacing:.2px;
            background:linear-gradient(135deg,#2563eb,#1e40af);box-shadow:0 10px 22px -8px rgba(37,99,235,.7);
            display:flex;align-items:center;justify-content:center;gap:.55rem;transition:transform .12s,box-shadow .12s,filter .12s;
        }
        .btn-login:hover{transform:translateY(-1px);box-shadow:0 14px 26px -8px rgba(37,99,235,.8);filter:brightness(1.05)}
        .btn-login:active{transform:none}
        .btn-login:disabled{opacity:.75;cursor:wait;transform:none}
        .spinner{width:18px;height:18px;border:2.5px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:none}
        .btn-login.loading .spinner{display:inline-block}
        @keyframes spin{to{transform:rotate(360deg)}}

        .foot{margin-top:1.2rem;padding-top:.9rem;border-top:1px solid #e8edf4;display:flex;justify-content:space-between;align-items:center;font-size:.75rem;color:#94a3b8}
        .foot b{color:#64748b}
        .secure{display:inline-flex;align-items:center;gap:.3rem}
        @media (max-width:480px){.login-card{padding:1.6rem 1.25rem 1.2rem;border-radius:18px}}
        @media (prefers-reduced-motion:reduce){.login-card{animation:none}.spinner{animation-duration:1.5s}}
    </style>
</head>
<body>
    <main class="login-card">
        <img src="{{ asset('assets/img/prodnegocix.png') }}" alt="NEGOCIX" class="logo">

        <div class="welcome">
            <h1>Bienvenue</h1>
            <p>Connectez-vous pour accéder à votre espace</p>
        </div>

        @if($errors->any())
            <div class="alert" role="alert">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Cookie::has('session_error'))
            <div class="alert" role="alert">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>{{ Cookie::get('session_error') }}</div>
            </div>
            @php Cookie::queue(Cookie::forget('session_error')); @endphp
        @endif

        <form action="{{ route('login.admin') }}" method="POST" id="loginForm" autocomplete="on">
            @csrf
            <div class="field">
                <label for="login">Nom d'utilisateur ou adresse e-mail</label>
                <div class="input-wrap">
                    <span class="ico"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" autocapitalize="none" spellcheck="false" placeholder="Votre identifiant">
                </div>
            </div>

            <div class="field">
                <label for="password">Mot de passe</label>
                <div class="input-wrap">
                    <span class="ico"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                    <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="••••••••">
                    <button type="button" class="toggle-pw" id="togglePw" aria-label="Afficher le mot de passe" title="Afficher / masquer">
                        <svg id="eyeOn" viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg id="eyeOff" viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                <div class="caps" id="capsWarn">⚠ Verrouillage majuscules activé</div>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                <span class="spinner"></span><span id="btnText">Se connecter</span>
            </button>
        </form>

        <div class="foot">
            <span class="secure"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Connexion sécurisée</span>
            <span>Version <b>2.0</b></span>
        </div>
    </main>

    <script>
    (function () {
        var form = document.getElementById('loginForm'), pw = document.getElementById('password'),
            btn = document.getElementById('btnLogin'), txt = document.getElementById('btnText'), caps = document.getElementById('capsWarn');
        document.getElementById('togglePw').addEventListener('click', function () {
            var show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            document.getElementById('eyeOn').style.display = show ? 'none' : '';
            document.getElementById('eyeOff').style.display = show ? '' : 'none';
            this.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
            pw.focus();
        });
        function checkCaps(e) { if (e.getModifierState) caps.classList.toggle('on', e.getModifierState('CapsLock')); }
        pw.addEventListener('keydown', checkCaps); pw.addEventListener('keyup', checkCaps);
        pw.addEventListener('blur', function () { caps.classList.remove('on'); });
        var sent = false;
        form.addEventListener('submit', function (e) {
            if (sent) { e.preventDefault(); return; }
            sent = true; btn.disabled = true; btn.classList.add('loading'); txt.textContent = 'Connexion…';
        });
        // retour arrière du navigateur : réactiver le bouton
        window.addEventListener('pageshow', function (e) { if (e.persisted) { sent = false; btn.disabled = false; btn.classList.remove('loading'); txt.textContent = 'Se connecter'; } });
    })();
    </script>
</body>
</html>