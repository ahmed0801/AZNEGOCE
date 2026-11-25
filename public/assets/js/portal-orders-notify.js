/* Alertes « Commandes portail » : popup en bas à droite sur toutes les pages de l'ERP, cloche, pastille du menu, son, alerte bureau. */
(function () {
    var me = document.currentScript;
    if (!me || window.__poNotify) return; window.__poNotify = true;
    var URL_FEED = me.getAttribute('data-feed'), URL_PAGE = me.getAttribute('data-page'), URL_QFEED = me.getAttribute('data-qfeed'), URL_QPAGE = me.getAttribute('data-qpage');
    var $ = function (id) { return document.getElementById(id); };
    var esc = function (v) { return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); };
    var fmt = function (n) { return Number(n).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };
    var ls = { get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } }, set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} } };
    var ss = { get: function (k) { try { return sessionStorage.getItem(k); } catch (e) { return null; } }, set: function (k, v) { try { sessionStorage.setItem(k, v); } catch (e) {} } };
    var busy = {o: false, q: false};
    /* Anti-doublon : une alerte n'est montrée qu'une fois, même si deux interrogations se croisent ou si plusieurs onglets sont ouverts (clé partagée entre onglets). */
    function firstTime(key) {
        var list = []; try { list = JSON.parse(ls.get('po_shown') || '[]'); } catch (e) { list = []; }
        if (list.indexOf(key) !== -1) return false;
        list.push(key); ls.set('po_shown', JSON.stringify(list.slice(-80))); return true;
    }
    var baseTitle = document.title.replace(/^\(\d+\)\s*/, ''), flash = null, pending = 0, soundOn = ls.get('po_sound') !== 'off', audio = null;

    /* ── styles du popup ── */
    var css = document.createElement('style');
    css.textContent = '#poStack{position:fixed;right:18px;bottom:18px;z-index:99999;display:flex;flex-direction:column;gap:10px;width:340px;max-width:calc(100vw - 24px);font-family:inherit}' +
        '.po-pop{background:#1a2035;color:#fff;border-radius:14px;padding:14px 16px 14px 18px;box-shadow:0 14px 44px rgba(0,0,0,.4);border-left:6px solid #31ce36;animation:poIn .35s cubic-bezier(.2,.8,.2,1);position:relative;cursor:pointer}' +
        '.po-pop.err{border-left-color:#f25961}.po-pop .t{font-size:.74rem;opacity:.7;letter-spacing:.02em}.po-pop .n{font-weight:800;font-size:1.1rem;margin:1px 0}.po-pop .c{font-size:.92rem}' +
        '.po-pop{cursor:default;padding-right:58px}.po-pop .x{position:absolute;top:8px;right:8px;width:38px;height:38px;border:2px solid rgba(255,255,255,.55);border-radius:50%;background:rgba(255,255,255,.14);color:#fff;font-size:1.7rem;font-weight:300;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;transition:background .15s,border-color .15s}.po-pop .x:hover,.po-pop .x:focus-visible{background:#f25961;border-color:#f25961;outline:none}' +
        '.po-pop .go{display:inline-block;margin-top:8px;background:#31ce36;color:#102a12;font-weight:700;font-size:.82rem;border-radius:8px;padding:7px 14px;text-decoration:none}' +
        '.po-pop .later{margin:8px 0 0 8px;border:1px solid rgba(255,255,255,.4);background:none;color:#fff;font-size:.82rem;font-weight:600;border-radius:8px;padding:6px 12px;cursor:pointer}.po-pop .later:hover{background:rgba(255,255,255,.14)}' +
        '#poAll{align-self:flex-end;border:0;border-radius:99px;background:#1a2035;color:#fff;font-size:.8rem;font-weight:700;padding:7px 14px;box-shadow:0 6px 18px rgba(0,0,0,.35);cursor:pointer;display:none}' +
        '.po-pop.out{animation:poOut .25s ease forwards}@keyframes poIn{from{opacity:0;transform:translateX(60px)}}@keyframes poOut{to{opacity:0;transform:translateX(60px)}}' +
        '@keyframes poRing{0%,100%{transform:rotate(0)}15%{transform:rotate(14deg)}30%{transform:rotate(-12deg)}45%{transform:rotate(8deg)}60%{transform:rotate(-5deg)}}#poBellBtn.ring i{animation:poRing 1s ease 3}';
    document.head.appendChild(css);
    var stack = document.createElement('div'); stack.id = 'poStack'; document.body.appendChild(stack);
    var allBtn = document.createElement('button'); allBtn.id = 'poAll'; allBtn.type = 'button'; allBtn.textContent = 'Tout fermer'; stack.appendChild(allBtn);
    function count() { return stack.querySelectorAll('.po-pop').length; }
    function sync() { allBtn.style.display = count() > 1 ? 'block' : 'none'; }
    allBtn.addEventListener('click', function () { [].slice.call(stack.querySelectorAll('.po-pop')).forEach(function (p) { p.remove(); }); sync(); });
    /* Aucune carte ne navigue d'elle-même : seul le bouton « Ouvrir » change de page (évite de quitter un travail en cours par un clic malheureux). */
    function mount(el) {
        var close = function () { el.classList.add('out'); setTimeout(function () { el.remove(); sync(); }, 260); };
        el.querySelector('.x').addEventListener('click', function (e) { e.stopPropagation(); close(); });
        el.querySelector('.x').setAttribute('title', 'Fermer cette alerte');
        var l = el.querySelector('.later'); if (l) l.addEventListener('click', close);
        var timer = setTimeout(close, 60000);
        el.addEventListener('mouseenter', function () { clearTimeout(timer); });
        el.addEventListener('mouseleave', function () { timer = setTimeout(close, 20000); });
        allBtn.insertAdjacentElement('beforebegin', el);
        while (count() > 4) stack.querySelector('.po-pop').remove();
        sync();
    }

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
            (o.erp_error ? '<div class="c" style="margin-top:4px;color:#ffb3b8">Création ERP à vérifier</div>' : '') + '<a class="go" href="' + esc(URL_PAGE) + '">Ouvrir les commandes</a><button type="button" class="later">Plus tard</button>';
        mount(el);
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
        list = list.filter(function (o) { return firstTime('o:' + (o.id || o.number)); });
        if (!list.length) return;
        list.forEach(popup); desk(list[list.length - 1]); beep();
        var bb = $('poBellBtn'); if (bb) { bb.classList.remove('ring'); void bb.offsetWidth; bb.classList.add('ring'); }
        if (!flash) { var on = false; flash = setInterval(function () { on = !on; document.title = on ? '🔔 Nouvelle commande !' : baseTitle; }, 900); setTimeout(function () { clearInterval(flash); flash = null; document.title = (pending ? '(' + pending + ') ' : '') + baseTitle; }, 15000); }
    }
    function poll() {
        if (busy.o) return; busy.o = true;
        var since = ss.get('po_seen');
        fetch(URL_FEED + '?since=' + encodeURIComponent(since === null ? -1 : since), {credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}})
            .then(function (r) { return r.ok ? r.json() : null; }).then(function (j) {
                if (!j) return; paint(j);
                if (since === null) ss.set('po_seen', j.last_id);                   // première interrogation de cet onglet : on ne rejoue pas l'existant
                else { if (j.last_id > parseInt(since, 10)) ss.set('po_seen', j.last_id); if (j.fresh && j.fresh.length) alertNew(j.fresh); }
            }).catch(function () {}).then(function () { busy.o = false; });
    }

    /* ── demandes de devis : pastille du menu + popup à l'arrivée d'une nouvelle demande ── */
    function qpopup(o) {
        var el = document.createElement('div'); el.className = 'po-pop';
        el.style.borderLeftColor = '#1572e8';
        el.innerHTML = '<button class="x" aria-label="Fermer">&times;</button><div class="t">' + esc(o.label || 'NOUVELLE DEMANDE DE DEVIS') + '</div><div class="n">' + esc(o.number) + '</div><div class="c">' + esc(o.customer || '') + '<br>' + (o.vehicle ? esc(o.vehicle) + ' · ' : '') + o.lines + ' pièce(s)</div><a class="go" style="background:#1572e8;color:#fff" href="' + esc(URL_QPAGE) + '">Ouvrir les devis</a><button type="button" class="later">Plus tard</button>';
        mount(el);
    }
    function qpoll() {
        if (!URL_QFEED || busy.q) return; busy.q = true;
        var since = ss.get('pq_seen2');
        fetch(URL_QFEED + '?since=' + encodeURIComponent(since === null ? -1 : since), {credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}})
            .then(function (r) { return r.ok ? r.json() : null; }).then(function (j) {
                if (!j) return;
                var sb = $('pqSideBadge'); if (sb) { sb.textContent = j.todo; sb.style.display = j.todo ? 'inline-block' : 'none'; }
                if (since === null) ss.set('pq_seen2', j.last_id);
                else { if (j.last_id > parseInt(since, 10)) ss.set('pq_seen2', j.last_id); if (j.fresh && j.fresh.length) { var nf = j.fresh.filter(function (o) { return firstTime('q:' + (o.ev || (o.id + o.label))); }); nf.forEach(qpopup); if (nf.length) beep(); } }
            }).catch(function () {}).then(function () { busy.q = false; });
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
        poll(); qpoll(); setInterval(function () { if (!document.hidden) { poll(); qpoll(); } }, 15000);
        document.addEventListener('visibilitychange', function () { if (!document.hidden) { poll(); qpoll(); } });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
