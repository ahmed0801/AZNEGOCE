/* Alertes « Commandes portail » : popup en bas à droite sur toutes les pages de l'ERP, cloche, pastille du menu, son, alerte bureau. */
(function () {
    var me = document.currentScript;
    if (!me || window.__poNotify) return; window.__poNotify = true;
    var URL_FEED = me.getAttribute('data-feed'), URL_PAGE = me.getAttribute('data-page');
    var $ = function (id) { return document.getElementById(id); };
    var esc = function (v) { return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); };
    var fmt = function (n) { return Number(n).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };
    var ls = { get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } }, set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} } };
    var ss = { get: function (k) { try { return sessionStorage.getItem(k); } catch (e) { return null; } }, set: function (k, v) { try { sessionStorage.setItem(k, v); } catch (e) {} } };
    var baseTitle = document.title.replace(/^\(\d+\)\s*/, ''), flash = null, pending = 0, soundOn = ls.get('po_sound') !== 'off', audio = null;

    /* ── styles du popup ── */
    var css = document.createElement('style');
    css.textContent = '#poStack{position:fixed;right:18px;bottom:18px;z-index:99999;display:flex;flex-direction:column;gap:10px;width:340px;max-width:calc(100vw - 24px);font-family:inherit}' +
        '.po-pop{background:#1a2035;color:#fff;border-radius:14px;padding:14px 16px 14px 18px;box-shadow:0 14px 44px rgba(0,0,0,.4);border-left:6px solid #31ce36;animation:poIn .35s cubic-bezier(.2,.8,.2,1);position:relative;cursor:pointer}' +
        '.po-pop.err{border-left-color:#f25961}.po-pop .t{font-size:.74rem;opacity:.7;letter-spacing:.02em}.po-pop .n{font-weight:800;font-size:1.1rem;margin:1px 0}.po-pop .c{font-size:.92rem}' +
        '.po-pop .x{position:absolute;top:6px;right:8px;border:0;background:none;color:#fff;opacity:.6;font-size:1.2rem;line-height:1;cursor:pointer}.po-pop .x:hover{opacity:1}' +
        '.po-pop .go{display:inline-block;margin-top:8px;background:#31ce36;color:#102a12;font-weight:700;font-size:.82rem;border-radius:8px;padding:5px 12px;text-decoration:none}' +
        '.po-pop.out{animation:poOut .25s ease forwards}@keyframes poIn{from{opacity:0;transform:translateX(60px)}}@keyframes poOut{to{opacity:0;transform:translateX(60px)}}' +
        '@keyframes poRing{0%,100%{transform:rotate(0)}15%{transform:rotate(14deg)}30%{transform:rotate(-12deg)}45%{transform:rotate(8deg)}60%{transform:rotate(-5deg)}}#poBellBtn.ring i{animation:poRing 1s ease 3}';
    document.head.appendChild(css);
    var stack = document.createElement('div'); stack.id = 'poStack'; document.body.appendChild(stack);

    function unlockAudio() {
        if (audio) return;
        try { var C = window.AudioContext || window.webkitAudioContext; audio = new C(); if (audio.state === 'suspended') audio.resume(); } catch (e) {}
    }
    ['click', 'keydown', 'touchstart'].forEach(function (ev) { document.addEventListener(ev, unlockAudio, {once: true, passive: true}); });
    function beep() {
        if (!soundOn || !audio) return;
        try {
            var t = audio.currentTime;
            [[880, 0], [1175, .17], [1568, .34]].forEach(function (n) { var o = audio.createOscillator(), g = audio.createGain(); o.type = 'sine'; o.frequency.value = n[0]; g.gain.setValueAtTime(.0001, t + n[1]); g.gain.exponentialRampToValueAtTime(.3, t + n[1] + .02); g.gain.exponentialRampToValueAtTime(.0001, t + n[1] + .3); o.connect(g); g.connect(audio.destination); o.start(t + n[1]); o.stop(t + n[1] + .32); });
        } catch (e) {}
    }
    function popup(o) {
        var el = document.createElement('div'); el.className = 'po-pop' + (o.erp_error ? ' err' : '');
        el.innerHTML = '<button class="x" aria-label="Fermer">&times;</button><div class="t">NOUVELLE COMMANDE PORTAIL</div><div class="n">' + esc(o.number) + '</div><div class="c">' + esc(o.customer || '') + '<br><b>' + fmt(o.total_ttc) + ' € TTC</b> · ' + esc(o.status_label || '') + '</div>' +
            (o.erp_error ? '<div class="c" style="margin-top:4px;color:#ffb3b8">Création ERP à vérifier</div>' : '') + '<a class="go" href="' + esc(URL_PAGE) + '">Ouvrir les commandes</a>';
        var close = function () { el.classList.add('out'); setTimeout(function () { el.remove(); }, 260); };
        el.querySelector('.x').addEventListener('click', function (e) { e.stopPropagation(); close(); });
        el.addEventListener('click', function () { location.href = URL_PAGE; });
        stack.appendChild(el); while (stack.children.length > 4) stack.removeChild(stack.firstChild);
        setTimeout(close, 45000);
    }
    function desk(o) {
        if (!('Notification' in window) || Notification.permission !== 'granted' || !document.hidden) return;
        try { var n = new Notification('Nouvelle commande portail ' + o.number, {body: (o.customer || '') + ' · ' + fmt(o.total_ttc) + ' € TTC'}); n.onclick = function () { window.focus(); location.href = URL_PAGE; }; } catch (e) {}
    }
    function paint(j) {
        pending = j.pending;
        var b = $('poBellN'), sb = $('poSideBadge'), list = $('poBellList');
        if (b) { b.textContent = j.pending > 99 ? '99+' : j.pending; b.style.display = j.pending ? 'flex' : 'none'; }
        if (sb) { sb.textContent = j.pending; sb.style.display = j.pending ? 'inline-block' : 'none'; }
        if (list) list.innerHTML = j.latest.length ? j.latest.map(function (o) {
            return '<a href="' + esc(URL_PAGE) + '" style="display:block;padding:11px 16px;border-bottom:1px solid #f0f0f0;color:inherit;text-decoration:none"><div style="display:flex;justify-content:space-between"><b>' + esc(o.number) + '</b><b>' + fmt(o.total_ttc) + ' €</b></div><div style="font-size:.85rem;color:#6c757d">' + esc(o.customer || '') + ' · ' + esc(o.ago) + (o.erp_error ? ' · <span style="color:#f25961">erreur ERP</span>' : '') + '</div></a>';
        }).join('') : '<div style="padding:22px 16px;color:#6c757d;text-align:center">Aucune commande en attente.</div>';
        if (!flash) document.title = (pending ? '(' + pending + ') ' : '') + baseTitle;
    }
    function alertNew(list) {
        list.forEach(popup); desk(list[list.length - 1]); beep();
        var bb = $('poBellBtn'); if (bb) { bb.classList.remove('ring'); void bb.offsetWidth; bb.classList.add('ring'); }
        if (!flash) { var on = false; flash = setInterval(function () { on = !on; document.title = on ? '🔔 Nouvelle commande !' : baseTitle; }, 900); setTimeout(function () { clearInterval(flash); flash = null; document.title = (pending ? '(' + pending + ') ' : '') + baseTitle; }, 15000); }
    }
    function poll() {
        var since = ss.get('po_seen');
        fetch(URL_FEED + '?since=' + encodeURIComponent(since === null ? -1 : since), {credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}})
            .then(function (r) { return r.ok ? r.json() : null; }).then(function (j) {
                if (!j) return; paint(j);
                if (since === null) ss.set('po_seen', j.last_id);                   // première interrogation de cet onglet : on ne rejoue pas l'existant
                else { if (j.last_id > parseInt(since, 10)) ss.set('po_seen', j.last_id); if (j.fresh && j.fresh.length) alertNew(j.fresh); }
            }).catch(function () {});
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('#poBellBtn');
        if (btn) { e.preventDefault(); e.stopPropagation(); var p = $('poBellPanel'); if (p) p.style.display = p.style.display === 'none' ? 'block' : 'none'; return; }
        if (e.target.id === 'poSound') { e.preventDefault(); soundOn = !soundOn; ls.set('po_sound', soundOn ? 'on' : 'off'); snd(); if (soundOn) { unlockAudio(); beep(); } return; }
        if (e.target.id === 'poDesk') { e.preventDefault(); Notification.requestPermission().then(function () { e.target.style.display = 'none'; }); return; }
        if (!e.target.closest('#poBellItem')) { var pp = $('poBellPanel'); if (pp) pp.style.display = 'none'; }
    });
    function snd() { var s = $('poSound'); if (s) s.textContent = 'Son : ' + (soundOn ? 'activé' : 'coupé'); }
    function init() {
        snd();
        var d = $('poDesk'); if (d && 'Notification' in window && Notification.permission === 'default') d.style.display = 'inline';
        poll(); setInterval(function () { if (!document.hidden) poll(); }, 15000);
        document.addEventListener('visibilitychange', function () { if (!document.hidden) poll(); });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
