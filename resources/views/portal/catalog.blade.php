@extends('portal.layout')
@section('title', 'Catalogue')
@section('body')
<header class="hd"><div class="hd-in">
  <a class="logo" href="{{ route('portal.catalog') }}" id="homeLink">
    @if($s->logo_url)<img src="{{ $s->logo_url }}" alt="">@else<span class="mark">{{ mb_strtoupper(mb_substr($s->company_name ?: 'P', 0, 1)) }}</span>@endif
    <b>{{ $s->company_name ?: 'Portail professionnel' }}</b>
  </a>

  <div class="omni" id="omni" role="search">
    <div class="omni-box">
      <svg class="ic"><use href="#i-search"/></svg>
      <input type="search" id="q" value="{{ $filters['q'] }}" placeholder="Référence, code-barres ou désignation" aria-label="Rechercher une pièce" autocomplete="off" autocapitalize="none" spellcheck="false" enterkeyhint="search" role="combobox" aria-expanded="false" aria-controls="sgPanel">
      <span class="spin sm" id="sgSpin" hidden></span>
      <kbd>/</kbd>
      <span class="omni-kind" id="kind" hidden></span>
      <button type="button" class="ibtn" id="clr" aria-label="Effacer" hidden><svg class="ic"><use href="#i-x"/></svg></button>
      <button type="button" class="ibtn js-bulkopen" aria-label="Coller une liste de références" title="Coller une liste de références"><svg class="ic"><use href="#i-paste"/></svg></button>
      <button type="button" class="ibtn js-scanopen" aria-label="Scanner un code-barres" title="Scanner un code-barres"><svg class="ic"><use href="#i-scan"/></svg></button>
    </div>
    <div class="panel" id="sgPanel" role="listbox" hidden></div>
  </div>

  <div class="tools">
    <button type="button" class="tbtn js-gofav" id="favBtn" title="Mes favoris"><svg class="ic"><use href="#i-star"/></svg><span class="lbl">Favoris</span><span class="n" id="favN" @if(!$favCount) hidden @endif>{{ $favCount }}</span></button>
    <button type="button" class="tbtn cartb" id="cartBtn" aria-label="Panier"><svg class="ic"><use href="#i-cart"/></svg><span class="lbl">Panier</span><span class="sum" id="cartSum"></span><span class="n" id="cartN" hidden>0</span></button>
    <div class="me">
      <button type="button" class="tbtn" id="meBtn" aria-haspopup="true" aria-label="Mon compte"><span class="av">{{ mb_strtoupper(mb_substr($pUser->name, 0, 1)) }}</span></button>
      <div class="menu" id="menu">
        <div class="who"><b>{{ $pUser->name }}</b><span>{{ $customer->name }}</span></div>
        @if($customer->discountGroup)<div class="who" style="padding-top:2px"><span>Profil tarifaire : <b style="display:inline">{{ $customer->discountGroup->name }}</b></span></div>@endif
        <a href="{{ route('portal.orders') }}">Mes commandes</a>
        @if($cs->show_finance)<a href="{{ route('portal.account') }}">Mon compte</a>@endif
        <a href="{{ route('portal.password') }}">Changer mon mot de passe</a>
        <form method="POST" action="{{ route('portal.logout') }}">@csrf<button type="submit">Se déconnecter</button></form>
      </div>
    </div>
  </div>
</div></header>

<div class="page">
  <div class="notes">
    @if(session('status'))<div class="note info">{{ session('status') }}</div>@endif
    @if($customer->blocked)<div class="note bad"><b>Compte bloqué.</b> Vous pouvez consulter le catalogue, mais les commandes sont suspendues.@if($s->phone) Contactez-nous au {{ $s->phone }}.@endif</div>@endif
    @if(!$cs->can_order)<div class="note warn">Votre compte est en <b>consultation seule</b> : vous pouvez rechercher les articles, mais pas commander.</div>@endif
  </div>

  <div class="shell {{ $browse ? 'is-browse' : '' }}" id="shell">
    <aside class="side" id="side" aria-label="Filtres">
      <div class="dr-h" style="padding:12px 0 6px;border:0" id="sideHead"><h2 style="margin:0;font-size:1.05rem">Filtres</h2><button type="button" class="ibtn js-sideclose fbtn" aria-label="Fermer"><svg class="ic"><use href="#i-x"/></svg></button></div>
      <form id="ff" onsubmit="return false"><div id="facets">@if(!$browse)@include('portal._facets')@endif</div></form>
      <button type="button" class="clearf js-reset">Effacer tous les filtres</button>
    </aside>

    <section>
      <div class="bar" id="bar" @if($browse) hidden @endif>
        <div class="bar-l"><span id="countTxt">@if(!$browse)<b>{{ number_format($pager->total(), 0, ',', ' ') }}</b> article{{ $pager->total() > 1 ? 's' : '' }}@endif</span></div>
        <div class="bar-r">
          <button type="button" class="tbtn fbtn js-sideopen"><svg class="ic"><use href="#i-filter"/></svg>Filtres</button>
          <select class="sel" id="sort" aria-label="Trier">
            <option value="relevance" {{ $filters['sort'] === 'relevance' ? 'selected' : '' }}>Pertinence</option>
            <option value="price_asc" {{ $filters['sort'] === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
            <option value="price_desc" {{ $filters['sort'] === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
            <option value="ref" {{ $filters['sort'] === 'ref' ? 'selected' : '' }}>Référence A à Z</option>
            <option value="stock" {{ $filters['sort'] === 'stock' ? 'selected' : '' }}>Plus de stock</option>
          </select>
          <div class="seg" role="group" aria-label="Affichage"><button type="button" data-view="list" aria-label="Liste"><svg class="ic"><use href="#i-list"/></svg></button><button type="button" data-view="grid" aria-label="Grille"><svg class="ic"><use href="#i-grid"/></svg></button></div>
        </div>
      </div>
      <div class="chips" id="chips"></div>
      <div id="results">@include('portal._results')</div>
    </section>
  </div>
</div>
<div class="foot">{{ $s->legal_footer }}</div>

<div class="veil" id="veil"></div>

<aside class="dr" id="drItem" aria-label="Fiche article" aria-hidden="true">
  <div class="dr-h"><h2>Fiche article</h2><button type="button" class="ibtn js-close" aria-label="Fermer"><svg class="ic"><use href="#i-x"/></svg></button></div>
  <div class="dr-b" id="drItemBody"></div>
</aside>

<aside class="dr" id="drCart" aria-label="Panier" aria-hidden="true">
  <div class="dr-h"><h2>Mon panier</h2><button type="button" class="ibtn js-close" aria-label="Fermer"><svg class="ic"><use href="#i-x"/></svg></button></div>
  <div class="dr-b" id="cartBody"></div>
  <div class="dr-f" id="cartFoot"></div>
</aside>

<div class="modal" id="mBulk" role="dialog" aria-label="Liste de références" aria-hidden="true">
  <div class="dr-h"><h2>Coller une liste de références</h2><button type="button" class="ibtn js-close" aria-label="Fermer"><svg class="ic"><use href="#i-x"/></svg></button></div>
  <div class="dr-b">
    <p class="mini" style="margin:0 0 10px">Une référence par ligne, avec la quantité si besoin : <span style="font-family:var(--mono)">04E115561T 4</span>. Les espaces, tirets et points sont ignorés. Vous pouvez coller depuis Excel.</p>
    <textarea id="bulkTxt" placeholder="04E115561T 4&#10;1K0 615 301 AA 2&#10;F026407183" spellcheck="false"></textarea>
    <div id="bulkOut"></div>
  </div>
  <div class="dr-f" style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap">
    <button type="button" class="btn2" id="bulkCheck">Vérifier la liste</button>
    <button type="button" class="btn2 pri" id="bulkAdd"><svg class="ic sm"><use href="#i-cart"/></svg>Ajouter au panier</button>
  </div>
</div>

<div class="modal scan" id="mScan" role="dialog" aria-label="Scanner" aria-hidden="true">
  <div class="dr-h"><h2>Scanner un code-barres</h2><button type="button" class="ibtn js-close" aria-label="Fermer"><svg class="ic"><use href="#i-x"/></svg></button></div>
  <div class="dr-b"><video id="scanVid" playsinline muted></video><p class="mini" id="scanMsg" style="margin:10px 0 0">Placez le code-barres dans l'image.</p></div>
</div>

<div class="toasts" id="toasts" aria-live="polite"></div>

<div class="busy" id="busy" role="status" aria-live="polite"><span class="spin lg"></span><span>Recherche en cours…</span></div>
<script>
(function () {
  'use strict';
  var URL_CAT = {!! json_encode(route('portal.catalog', [], false)) !!},
      URL_SUG = {!! json_encode(route('portal.suggest', [], false)) !!},
      URL_ITEM = {!! json_encode(route('portal.item', ['item' => 999999999], false)) !!}.replace('999999999', '__ID__'),
      URL_EQ = {!! json_encode(route('portal.equivalents', ['item' => 999999999], false)) !!}.replace('999999999', '__ID__'),
      URL_FAV = {!! json_encode(route('portal.favorite', ['item' => 999999999], false)) !!}.replace('999999999', '__ID__'),
      URL_ADD = {!! json_encode(route('portal.cart.add', [], false)) !!},
      URL_UPD = {!! json_encode(route('portal.cart.update', [], false)) !!},
      URL_CARTGET = {!! json_encode(route('portal.cart', [], false)) !!},
      URL_CLR = {!! json_encode(route('portal.cart.clear', [], false)) !!},
      URL_BULK = {!! json_encode(route('portal.bulk', [], false)) !!},
      URL_ORDER = {!! json_encode(route('portal.order.store', [], false)) !!};
  var OPT = {!! json_encode(['brands' => collect($options['brands'])->pluck('name', 'id'), 'categories' => collect($options['categories'])->pluck('name', 'id'), 'subs' => array_map(function ($m) { return $m['name']; }, $options['subMap'] ?? []), 'subParent' => array_map(function ($m) { return $m['parent']; }, $options['subMap'] ?? [])], 15) !!};
  var CSRF = document.querySelector('meta[name=csrf-token]').content;
  var st = {!! json_encode(['q' => $filters['q'], 'brand' => $filters['brand'], 'category' => $filters['category'], 'sub' => $filters['sub'], 'stock' => $filters['stock'], 'fav' => $filters['fav'], 'sort' => $filters['sort'], 'page' => (int) request('page', 1), 'all' => false], 15) !!};
  var cart = {!! json_encode($cart, 15) !!};
  var favN = {{ (int) $favCount }};
  var FULFIL = {!! json_encode($cs->fulfillment ?: 'pickup') !!};   // mis à jour à chaque rafraîchissement du panier

  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var esc = function (v) { return String(v == null ? '' : v).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); };
  var fmt = function (n) { return Number(n).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };
  var store = { get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } }, set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} } };
  var $q = $('#q'), $res = $('#results'), $panel = $('#sgPanel'), $veil = $('#veil');

  /* ───────── requêtes ───────── */
  function post(url, data) {
    return fetch(url, { method: 'POST', credentials: 'same-origin', headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest'}, body: JSON.stringify(data || {}) })
      .then(function (r) {
        if (r.status === 401 || r.status === 419) { location.reload(); throw new Error('session'); }
        return r.json().then(function (j) { if (!r.ok) { j.__err = true; } return j; });
      });
  }
  function getJson(url) {
    return fetch(url, { credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'} })
      .then(function (r) { if (r.status === 401 || r.status === 419) { location.reload(); throw new Error('session'); } return r.json(); });
  }
  function getHtml(url) {
    return fetch(url, { credentials: 'same-origin', headers: {'X-Requested-With': 'XMLHttpRequest'} })
      .then(function (r) { if (r.status === 401 || r.status === 419) { location.reload(); throw new Error('session'); } if (!r.ok) throw new Error('http'); return r.text(); });
  }

  /* ───────── toasts ───────── */
  function toast(msg, opts) {
    opts = opts || {};
    var el = document.createElement('div'); el.className = 'toast' + (opts.err ? ' err' : '');
    el.innerHTML = '<span>' + esc(msg) + '</span>' + (opts.action ? '<button type="button">' + esc(opts.action) + '</button>' : '');
    if (opts.action) el.querySelector('button').onclick = function () { opts.run(); el.remove(); };
    $('#toasts').appendChild(el);
    setTimeout(function () { el.remove(); }, opts.ms || 3800);
  }

  /* ───────── surbrillance des termes ───────── */
  function terms() { return st.q.split(/\s+/).filter(function (t) { return t.length > 1; }).slice(0, 6); }
  function hlHtml(text, q) {
    var t = esc(text), ts = String(q || '').split(/\s+/).filter(function (x) { return x.length > 1; }).slice(0, 6);
    ts.forEach(function (w) { try { t = t.replace(new RegExp('(' + w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&').replace(/&/g, '&amp;') + ')', 'ig'), '<mark>$1</mark>'); } catch (e) {} });
    return t;
  }
  function highlight(root) {
    var ts = terms(); if (!ts.length) return;
    $$('.plate.js-open, .name.js-open', root).forEach(function (n) { n.innerHTML = hlHtml(n.textContent, st.q); });
  }

  /* ───────── état -> URL ───────── */
  function qs(extra) {
    var p = new URLSearchParams();
    if (st.q) p.set('q', st.q);
    st.brand.forEach(function (v) { p.append('brand[]', v); });
    st.category.forEach(function (v) { p.append('category[]', v); });
    st.sub.forEach(function (v) { p.append('sub[]', v); });
    if (st.stock) p.set('stock', '1');
    if (st.fav) p.set('fav', '1');
    if (st.sort && st.sort !== 'relevance') p.set('sort', st.sort);
    if (st.all) p.set('all', '1');
    if (st.page > 1) p.set('page', st.page);
    if (extra) Object.keys(extra).forEach(function (k) { p.set(k, extra[k]); });
    return p.toString();
  }
  function fromUrl() {
    var p = new URLSearchParams(location.search);
    st.q = (p.get('q') || '').slice(0, 120);
    st.brand = p.getAll('brand[]'); st.category = p.getAll('category[]'); st.sub = p.getAll('sub[]');
    st.stock = p.get('stock') === '1'; st.fav = p.get('fav') === '1';
    st.sort = p.get('sort') || 'relevance'; st.all = p.get('all') === '1'; st.page = parseInt(p.get('page') || '1', 10) || 1;
  }
  function isBrowse() { return !st.all && !st.q && !st.brand.length && !st.category.length && !st.sub.length && !st.stock && !st.fav; }

  function renderChips() {
    var out = [];
    if (st.q) out.push(['q', '', 'Recherche : ' + st.q]);
    st.brand.forEach(function (v) { out.push(['brand', v, OPT.brands[v] || 'Marque']); });
    st.category.forEach(function (v) { out.push(['category', v, OPT.categories[v] || 'Famille']); });
    st.sub.forEach(function (v) { out.push(['sub', v, OPT.subs[v] || 'Sous-famille']); });
    if (st.stock) out.push(['stock', '', 'En stock']);
    if (st.fav) out.push(['fav', '', 'Mes favoris']);
    $('#chips').innerHTML = out.map(function (c) { return '<span class="chip">' + esc(c[2]) + '<button type="button" data-k="' + c[0] + '" data-v="' + esc(c[1]) + '" aria-label="Retirer"><svg class="ic sm"><use href="#i-x"/></svg></button></span>'; }).join('');
  }

  var seq = 0, view = store.get('pv') === 'grid' ? 'grid' : 'list';
  function applyView() {
    $$('.js-parts').forEach(function (n) { n.classList.toggle('is-grid', view === 'grid'); });
    $$('.seg button').forEach(function (b) { b.classList.toggle('on', b.getAttribute('data-view') === view); });
  }
  var calm = window.matchMedia('(prefers-reduced-motion:reduce)').matches;
  function countUp() {
    $$('.cu').forEach(function (el) {
      var to = parseInt(el.getAttribute('data-to'), 10) || 0; if (calm || to < 50) return;
      var t0 = null; (function f(ts) { if (!t0) t0 = ts; var k = Math.min(1, (ts - t0) / 1000), e = 1 - Math.pow(1 - k, 3); el.textContent = Math.round(to * e).toLocaleString('fr-FR'); if (k < 1) requestAnimationFrame(f); })(performance.now());
    });
  }
  function stagger() {
    if (calm) return; $res.classList.remove('enter');
    $$('.js-parts .part', $res).slice(0, 12).forEach(function (p, i) { p.style.setProperty('--i', i); });
    void $res.offsetWidth; $res.classList.add('enter');
  }
  function afterRender(first) { applyView(); highlight($res); syncCartMarks(); countUp(); if (!first) stagger(); }

  var busyT = null;
  function busyShow(on) { clearTimeout(busyT); if (on) busyT = setTimeout(function () { $('#busy').classList.add('on'); }, 120); else $('#busy').classList.remove('on'); }
  function load(push, keepScroll) {
    var my = ++seq, browse = isBrowse();
    $('#shell').classList.toggle('is-browse', browse);
    $('#bar').hidden = browse;
    $('#shell').classList.add('loading'); busyShow(true);
    renderChips();
    var q = qs(); if (push !== false) history.pushState(null, '', URL_CAT + (q ? '?' + q : ''));
    getJson(URL_CAT + '?' + qs({partial: 1})).then(function (j) {
      if (my !== seq) return;
      $res.innerHTML = j.results;
      if (!j.browse) { $('#facets').innerHTML = j.facets; $('#countTxt').innerHTML = '<b>' + Number(j.total).toLocaleString('fr-FR') + '</b> article' + (j.total > 1 ? 's' : ''); }
      $('#shell').classList.remove('loading'); busyShow(false); afterRender();
      if (!keepScroll) window.scrollTo({top: 0, behavior: 'smooth'});
    }).catch(function (e) { if (my === seq) { $('#shell').classList.remove('loading'); busyShow(false); if (e.message !== 'session') toast('Chargement impossible. Vérifiez votre connexion.', {err: true}); } });
  }
  function setQ(v) { $q.value = v; st.q = v; syncBox(); }
  function go(patch) { if (!('sub' in patch) && ('category' in patch || 'brand' in patch || 'q' in patch)) patch.sub = []; Object.keys(patch).forEach(function (k) { st[k] = patch[k]; }); if (!('page' in patch)) st.page = 1; closePanel(); load(true); }

  window.addEventListener('popstate', function () { fromUrl(); setQ(st.q); $('#sort').value = st.sort; load(false, true); });

  /* ───────── omnibox ───────── */
  var sgTimer, sgSeq = 0, items = [], active = -1;
  function recents() { try { return JSON.parse(store.get('pr') || '[]'); } catch (e) { return []; } }
  function pushRecent(v) { v = v.trim(); if (v.length < 2) return; var r = recents().filter(function (x) { return x !== v; }); r.unshift(v); store.set('pr', JSON.stringify(r.slice(0, 6))); }
  function syncBox() { var has = $q.value.length > 0; $('#clr').hidden = !has; if (!has) $('#kind').hidden = true; }
  function openPanel() { $panel.hidden = false; $q.setAttribute('aria-expanded', 'true'); }
  function closePanel() { $panel.hidden = true; $q.setAttribute('aria-expanded', 'false'); active = -1; }
  function setKind(k) {
    var el = $('#kind'); var map = {ref: 'Référence', barcode: 'Code-barres'};
    if (map[k] && $q.value.trim().length >= 3) { el.textContent = map[k]; el.hidden = false; } else el.hidden = true;
  }
  function renderIdle() {
    var r = recents(), h = '';
    if (r.length) {
      h += '<div class="sg-h"><span>Recherches récentes</span><button type="button" data-act="clr-recent">Effacer</button></div><div class="sg-chips">' +
        r.map(function (x) { return '<button type="button" class="chipb" data-q="' + esc(x) + '"><svg class="ic sm"><use href="#i-clock"/></svg>' + esc(x) + '</button>'; }).join('') + '</div>';
    }
    h += '<div class="sg-empty"><b>Cherchez comme au comptoir.</b>Référence constructeur ou équipementier (les espaces et tirets sont ignorés), code-barres, ou plusieurs mots de la désignation : <i>plaquette avant golf</i>.</div>';
    $panel.innerHTML = h; items = []; openPanel();
  }
  function renderSug(j, q) {
    setKind(j.kind);
    var h = '';
    if (j.articles && j.articles.length) {
      h += '<div class="sg-h"><span>' + (j.approx ? 'Articles approchants' : 'Articles') + '</span><span>' + (j.more ? '6+' : Number(j.total).toLocaleString('fr-FR')) + ' résultat' + (j.more || j.total > 1 ? 's' : '') + '</span></div>';
      j.articles.forEach(function (a) {
        var stt = a.stock.state, lab = a.stock.label || (stt === 'out' ? 'Rupture' : 'Disponible');
        h += '<button type="button" class="sg-i" role="option" data-open="' + a.id + '"><span class="sg-ref">' + hlHtml(a.code, q) + (a.brand ? ' <small style="font-family:var(--font);font-weight:600;color:var(--ink3)">' + esc(a.brand) + '</small>' : '') + '</span>' +
          '<span class="sg-name">' + hlHtml(a.name, q) + '</span><span class="sg-side">' + (a.price ? '<span class="sg-p">' + fmt(a.price.net_ht) + ' €</span>' : '') + '<span class="stk ' + stt + '"><i></i>' + esc(lab) + '</span></span></button>';
      });
    }
    if (j.brands && j.brands.length) {
      h += '<div class="sg-h"><span>Marques</span></div><div class="sg-chips">' + j.brands.map(function (b) { return '<button type="button" class="chipb" data-brand="' + esc(b.id) + '">' + esc(b.name) + '<small>' + b.n + '</small></button>'; }).join('') + '</div>';
    }
    if (j.categories && j.categories.length) {
      h += '<div class="sg-h"><span>Familles</span></div><div class="sg-chips">' + j.categories.map(function (c) { return '<button type="button" class="chipb" data-cat="' + esc(c.id) + '">' + esc(c.name) + '<small>' + c.n + '</small></button>'; }).join('') + '</div>';
    }
    if (!h) {
      h = '<div class="sg-empty"><b>Aucun résultat pour « ' + esc(q) + ' »</b>Essayez une partie de la référence, moins de mots, ou <button type="button" class="link js-bulkopen" style="display:inline">collez une liste de références</button>.</div>';
    } else {
      h += '<div class="sg-foot"><button type="button" class="go" data-act="all">' + (j.more ? 'Voir tous les résultats' : 'Voir les ' + Number(j.total).toLocaleString('fr-FR') + ' résultats') + '</button><span><kbd>↑</kbd> <kbd>↓</kbd> naviguer <kbd>Entrée</kbd> valider</span></div>';
    }
    $panel.innerHTML = h; items = $$('.sg-i', $panel); active = -1; openPanel();
  }
  function spinSug(on) { $('#sgSpin').hidden = !on; $('.omni-box').classList.toggle('is-busy', on); }
  function suggest() {
    var v = $q.value.trim(); syncBox();
    if (v.length < 2) { $('#kind').hidden = true; if (document.activeElement === $q) renderIdle(); return; }
    var my = ++sgSeq; spinSug(true);
    getJson(URL_SUG + '?q=' + encodeURIComponent(v)).then(function (j) { if (my === sgSeq) spinSug(false); if (my === sgSeq && document.activeElement === $q) renderSug(j, v); }).catch(function () { if (my === sgSeq) spinSug(false); });
  }
  function highlightActive() { items.forEach(function (n, i) { n.classList.toggle('act', i === active); if (i === active) n.scrollIntoView({block: 'nearest'}); }); }
  function submitSearch() {
    var v = $q.value.trim(); pushRecent(v); st.q = v; st.brand = []; st.category = []; st.sub = []; st.fav = false; st.all = false;
    go({q: v, brand: [], category: [], fav: false}); $q.blur();
  }

  $q.addEventListener('input', function () { clearTimeout(sgTimer); sgTimer = setTimeout(suggest, 200); syncBox(); });
  $q.addEventListener('focus', function () { if ($q.value.trim().length >= 2) suggest(); else renderIdle(); });
  $q.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowDown' && items.length) { e.preventDefault(); active = (active + 1) % items.length; highlightActive(); }
    else if (e.key === 'ArrowUp' && items.length) { e.preventDefault(); active = (active - 1 + items.length) % items.length; highlightActive(); }
    else if (e.key === 'Enter') { e.preventDefault(); if (active >= 0 && items[active]) { openItem(items[active].getAttribute('data-open')); closePanel(); } else submitSearch(); }
    else if (e.key === 'Escape') { closePanel(); $q.blur(); }
  });
  $('#clr').addEventListener('click', function () { setQ(''); $q.focus(); go({q: ''}); renderIdle(); });
  document.addEventListener('click', function (e) { if (!e.target.closest('#omni')) closePanel(); });
  document.addEventListener('keydown', function (e) {
    if (e.key === '/' && !/INPUT|TEXTAREA|SELECT/.test(document.activeElement.tagName)) { e.preventDefault(); $q.focus(); $q.select(); }
    if (e.key === 'Escape') closeAll();
  });

  $panel.addEventListener('click', function (e) {
    var b = e.target.closest('button'); if (!b) return;
    if (b.hasAttribute('data-open')) { openItem(b.getAttribute('data-open')); closePanel(); }
    else if (b.hasAttribute('data-q')) { setQ(b.getAttribute('data-q')); submitSearch(); }
    else if (b.hasAttribute('data-brand')) { setQ(''); go({q: '', brand: [b.getAttribute('data-brand')], category: []}); }
    else if (b.hasAttribute('data-cat')) { setQ(''); go({q: '', category: [b.getAttribute('data-cat')], brand: []}); }
    else if (b.getAttribute('data-act') === 'all') submitSearch();
    else if (b.getAttribute('data-act') === 'clr-recent') { store.set('pr', '[]'); renderIdle(); }
  });

  /* ───────── page : filtres, tri, pagination, chips ───────── */
  $('#sort').addEventListener('change', function () { go({sort: this.value}); });
  $('#facets').addEventListener('change', function () {
    var ff = $('#ff'), b = [], c = [];
    $$('input[name="brand[]"]:checked', ff).forEach(function (i) { b.push(i.value); });
    $$('input[name="category[]"]:checked', ff).forEach(function (i) { c.push(i.value); });
    var sb = $$('input[name="sub[]"]:checked', ff).map(function (i) { return i.value; }).filter(function (v) { return !!OPT.subParent[v] && c.indexOf(OPT.subParent[v]) >= 0; });
    st.brand = b; st.category = c; st.sub = sb; st.stock = !!$('input[name=stock]:checked', ff); st.fav = !!$('input[name=fav]:checked', ff); st.page = 1; load(true);
  });
  $('#facets').addEventListener('input', function (e) {
    if (!e.target.classList.contains('js-fsearch')) return;
    var v = e.target.value.toLowerCase(), sec = e.target.closest('.fs');
    $$('.fopt', sec).forEach(function (l) { l.hidden = v ? l.getAttribute('data-name').indexOf(v) < 0 : l.classList.contains('extra'); });
  });
  $('#facets').addEventListener('click', function (e) {
    if (!e.target.classList.contains('js-more')) return;
    var sec = e.target.closest('.fs'); $$('.fopt', sec).forEach(function (l) { l.hidden = false; }); e.target.remove();
  });
  $('#chips').addEventListener('click', function (e) {
    var b = e.target.closest('button'); if (!b) return; var k = b.getAttribute('data-k'), v = b.getAttribute('data-v');
    if (k === 'q') { setQ(''); st.q = ''; }
    else if (k === 'brand') st.brand = st.brand.filter(function (x) { return x !== v; });
    else if (k === 'category') { st.category = st.category.filter(function (x) { return x !== v; }); st.sub = st.sub.filter(function (s) { return st.category.indexOf(OPT.subParent[s]) >= 0; }); }
    else if (k === 'sub') st.sub = st.sub.filter(function (x) { return x !== v; });
    else st[k] = false;
    st.page = 1; load(true);
  });
  $$('.seg button').forEach(function (b) { b.addEventListener('click', function () { view = b.getAttribute('data-view'); store.set('pv', view); applyView(); }); });
  function resetAll() { setQ(''); st.brand = []; st.category = []; st.sub = []; st.stock = false; st.fav = false; st.sort = 'relevance'; $('#sort').value = 'relevance'; st.all = false; st.page = 1; closeAll(); load(true); }

  /* ───────── tiroirs, modales ───────── */
  var openEl = null;
  function openLayer(el) { closeAll(true); el.classList.add('on'); el.setAttribute('aria-hidden', 'false'); $veil.classList.add('on'); openEl = el; document.body.style.overflow = 'hidden'; }
  function closeAll(keep) {
    ['drItem', 'drCart', 'mBulk', 'mScan'].forEach(function (id) { var e = document.getElementById(id); e.classList.remove('on'); e.setAttribute('aria-hidden', 'true'); });
    $('#side').classList.remove('on'); if (!keep) { $veil.classList.remove('on'); document.body.style.overflow = ''; }
    stopScan(); openEl = null; $('#menu').classList.remove('open');
  }
  $veil.addEventListener('click', function () { closeAll(); });
  document.addEventListener('click', function (e) { if (e.target.closest('.js-close')) closeAll(); });
  $('#meBtn').addEventListener('click', function (e) { e.stopPropagation(); $('#menu').classList.toggle('open'); });
  document.addEventListener('click', function (e) { if (!e.target.closest('.me')) $('#menu').classList.remove('open'); });

  function openItem(id) {
    $('#drItemBody').innerHTML = '<div class="sk"></div><div class="sk"></div><div class="sk"></div>';
    openLayer($('#drItem'));
    getHtml(URL_ITEM.replace('__ID__', encodeURIComponent(id))).then(function (h) { $('#drItemBody').innerHTML = h; highlight($('#drItemBody')); syncCartMarks(); })
      .catch(function () { $('#drItemBody').innerHTML = '<div class="sg-empty"><b>Article indisponible</b>Cette fiche ne peut pas être affichée.</div>'; });
  }

  /* ───────── actions sur les articles (délégation) ───────── */
  document.addEventListener('click', function (e) {
    var t = e.target;
    var op = t.closest('.js-open'); if (op) { openItem(op.getAttribute('data-id')); return; }
    var inc = t.closest('.js-inc'), dec = t.closest('.js-dec');
    if (inc || dec) { var inp = (inc || dec).parentNode.querySelector('.js-qty'); var n = parseInt(inp.value || '1', 10) || 1; inp.value = Math.max(1, Math.min(9999, n + (inc ? 1 : -1))); return; }
    var add = t.closest('.js-add'); if (add) { addToCart(add); return; }
    var fv = t.closest('.js-fav'); if (fv) { toggleFav(fv.getAttribute('data-id')); return; }
    var eq = t.closest('.js-eq'); if (eq) { toggleEq(eq); return; }
    var pg = t.closest('.js-page'); if (pg && !pg.disabled) { st.page = parseInt(pg.getAttribute('data-page'), 10); load(true); return; }
    if (t.closest('.js-reset')) { resetAll(); return; }
    if (t.closest('.js-opencart')) { closeAll(); openCart(); return; }
    var ro = t.closest('.js-reorder'); if (ro) { ro.disabled = true; post(ro.getAttribute('data-url')).then(function (j) { if (j.ok) { location.href = URL_CAT + '?cart=1'; } else { ro.disabled = false; toast(j.message || 'Impossible pour le moment.'); } }).catch(function () { ro.disabled = false; }); return; }
    if (t.closest('.js-gofav')) { closeAll(); setQ(''); st.q = ''; st.brand = []; st.category = []; st.sub = []; st.stock = false; st.fav = true; st.page = 1; load(true); return; }
    var br = t.closest('.js-brand'); if (br) { go({q: '', brand: [br.getAttribute('data-id')], category: []}); return; }
    var sbt = t.closest('.js-sub'); if (sbt) { e.preventDefault(); go({q: '', category: [sbt.getAttribute('data-cat')], sub: [sbt.getAttribute('data-id')], brand: []}); return; }
    var ct = t.closest('.js-cat'); if (ct) { e.preventDefault(); go({q: '', category: [ct.getAttribute('data-id')], brand: []}); return; }
    if (t.closest('.js-bulkopen')) { closePanel(); openLayer($('#mBulk')); setTimeout(function () { $('#bulkTxt').focus(); }, 60); return; }
    if (t.closest('.js-scanopen')) { closePanel(); startScan(); return; }
    if (t.closest('.js-sideopen')) { openLayer($('#side')); return; }
    if (t.closest('.js-sideclose')) { closeAll(); return; }
  });
  document.addEventListener('change', function (e) { if (e.target.classList.contains('js-qty')) { var n = parseInt(e.target.value || '1', 10); e.target.value = isNaN(n) ? 1 : Math.max(1, Math.min(9999, n)); } });
  document.addEventListener('keydown', function (e) { if (e.key === 'Enter' && e.target.classList.contains('js-qty')) { e.preventDefault(); var row = e.target.closest('.part'); var b = row && row.querySelector('.js-add'); if (b) b.click(); } });

  function addToCart(btn) {
    var row = btn.closest('.part'); var q = parseInt(row.querySelector('.js-qty').value || '1', 10) || 1;
    btn.disabled = true;
    post(URL_ADD, {item_id: parseInt(btn.getAttribute('data-id'), 10), qty: q}).then(function (j) {
      btn.disabled = false;
      if (!j.ok) { toast(j.message || (j.errors && Object.values(j.errors)[0][0]) || 'Ajout impossible.', {err: true}); return; }
      flyToCart(btn); setCart(j.cart); var old = btn.innerHTML; btn.classList.add('done'); btn.innerHTML = '<svg class="ic sm"><use href="#i-check"/></svg>Ajouté';
      setTimeout(function () { btn.classList.remove('done'); btn.innerHTML = old; }, 1300);
      toast(q + ' article' + (q > 1 ? 's' : '') + ' ajouté' + (q > 1 ? 's' : '') + ' au panier', {action: 'Voir', run: openCart, ms: 2600});
    }).catch(function (e) { btn.disabled = false; if (e.message !== 'session') toast('Ajout impossible. Réessayez.', {err: true}); });
  }
  function flyToCart(from) {
    if (calm || !document.body.animate) return;
    var a = from.getBoundingClientRect(), b = $('#cartBtn').getBoundingClientRect(), d = document.createElement('div'); d.className = 'fly';
    document.body.appendChild(d);
    var x0 = a.left + a.width / 2 - 8, y0 = a.top + a.height / 2 - 8, x1 = b.left + b.width / 2 - 8, y1 = b.top + b.height / 2 - 8;
    d.animate([{transform: 'translate(' + x0 + 'px,' + y0 + 'px) scale(1)', opacity: 1}, {transform: 'translate(' + ((x0 + x1) / 2) + 'px,' + (Math.min(y0, y1) - 60) + 'px) scale(1.15)', opacity: 1, offset: .5}, {transform: 'translate(' + x1 + 'px,' + y1 + 'px) scale(.3)', opacity: .2}], {duration: 650, easing: 'cubic-bezier(.4,0,.2,1)'}).onfinish = function () { d.remove(); };
  }
  function typePlaceholder() {
    if (calm || window.matchMedia('(max-width:760px)').matches) return;
    var ex = ['0 986 424 797', 'plaquette avant golf 7', 'filtre à huile 1.5 dci', '3165143000000', 'disque frein 280 mm'], i = 0, base = 'Essayez : ';
    (function next() {
      if (document.activeElement === $q || $q.value) { setTimeout(next, 1500); return; }
      var s = ex[i++ % ex.length], n = 0;
      (function tick() { if (document.activeElement === $q || $q.value) return setTimeout(next, 1500); $q.placeholder = base + s.slice(0, ++n); if (n < s.length) setTimeout(tick, 55); else setTimeout(next, 2200); })();
    })();
  }
  function toggleFav(id) {
    post(URL_FAV.replace('__ID__', encodeURIComponent(id)), {}).then(function (j) {
      if (!j.ok) return;
      $$('.js-fav[data-id="' + id + '"]').forEach(function (b) { b.classList.toggle('on', j.fav); b.setAttribute('aria-pressed', j.fav ? 'true' : 'false'); });
      favN = j.count; var n = $('#favN'); n.textContent = favN; n.hidden = !favN;
      toast(j.fav ? 'Ajouté à vos favoris' : 'Retiré de vos favoris', {ms: 1800});
    });
  }
  function toggleEq(btn) {
    var part = btn.closest('.part'), box = part.querySelector('.eqbox');
    if (!box.hidden) { box.hidden = true; return; }
    box.hidden = false;
    if (box.getAttribute('data-ok')) return;
    box.innerHTML = '<div class="parts"><div class="sk"></div><div class="sk"></div></div>';
    getHtml(URL_EQ.replace('__ID__', encodeURIComponent(btn.getAttribute('data-id')))).then(function (h) { box.innerHTML = h; box.setAttribute('data-ok', '1'); applyView(); syncCartMarks(); })
      .catch(function () { box.innerHTML = '<div class="sg-empty">Chargement impossible.</div>'; });
  }

  /* ───────── panier ───────── */
  function syncCartMarks() {
    var m = {}; cart.lines.forEach(function (l) { m[l.id] = l.qty; });
    $$('.part[data-id]').forEach(function (p) {
      var q = m[p.getAttribute('data-id')], el = p.querySelector('.incart'); if (!el) return;
      el.hidden = !q; if (q) el.textContent = q + ' dans le panier';
    });
  }
  function setCart(c) {
    cart = c; if (c.fulfillment) FULFIL = c.fulfillment; var n = $('#cartN');
    n.textContent = c.units; n.hidden = !c.units;
    $('#cartSum').textContent = c.prices && c.total_ht ? fmt(c.total_ht) + ' €' : '';
    var b = $('#cartBtn'); b.classList.remove('bump'); void b.offsetWidth; b.classList.add('bump');
    syncCartMarks(); if ($('#drCart').classList.contains('on')) renderCart();
  }
  function renderCart() {
    var c = cart, body = $('#cartBody'), foot = $('#cartFoot');
    if (!c.lines.length) {
      body.innerHTML = '<div class="empty" style="border:0"><b>Votre panier est vide</b>Cherchez une pièce ou collez une liste de références pour le remplir.<div class="acts"><button type="button" class="btn2 js-bulkopen"><svg class="ic sm"><use href="#i-paste"/></svg>Coller une liste</button></div></div>';
      foot.innerHTML = ''; foot.hidden = true; return;
    }
    foot.hidden = false;
    body.innerHTML = c.lines.map(function (l) {
      return '<div class="cl" data-id="' + l.id + '"><div><span class="plate">' + esc(l.code) + '</span>' + (l.brand ? ' <span class="brand">' + esc(l.brand) + '</span>' : '') + '<div class="nm">' + esc(l.name) + '</div>' +
        '<div class="qty"><button type="button" data-q="-1" aria-label="Moins">−</button><input type="number" inputmode="numeric" min="0" max="9999" value="' + l.qty + '" aria-label="Quantité"><button type="button" data-q="1" aria-label="Plus">+</button></div></div>' +
        '<div><div class="lt">' + (l.line_ht != null ? fmt(l.line_ht) + ' €<small>' + fmt(l.unit_ht) + ' € l\'unité</small>' : '') + '</div><button type="button" class="rm" data-rm="1" aria-label="Retirer"><svg class="ic sm"><use href="#i-trash"/></svg></button></div>' +
        (l.exceeds ? '<div class="warn">Quantité supérieure au stock : livraison partielle ou différée possible.</div>' : '') + '</div>';
    }).join('');
    var h = '';
    if (c.prices) {
      h += '<div class="tot"><div><span>Total HT</span><b>' + fmt(c.total_ht) + ' €</b></div><div><span>TVA</span><span>' + fmt(c.total_tva) + ' €</span></div><div class="ttl"><span>Total TTC</span><span>' + fmt(c.total_ttc) + ' €</span></div></div>';
      if (c.min_order > 0) {
        var pct = Math.min(100, Math.round((c.total_ht / c.min_order) * 100));
        h += '<div class="prog"><i style="width:' + pct + '%"></i></div><div class="mini" style="margin-bottom:10px">' + (c.missing > 0 ? 'Encore ' + fmt(c.missing) + ' € HT pour atteindre le minimum de commande (' + fmt(c.min_order) + ' €).' : 'Minimum de commande atteint.') + '</div>';
      }
    }
    if (c.block) h += '<div class="note warn" style="margin-bottom:10px">' + esc(c.block) + '</div>';
    var can = c.checkout && !c.block && !(c.missing > 0);
    h += '<button type="button" class="cta" id="checkout"' + (can ? '' : ' disabled') + '>' + (c.checkout ? 'Valider ma commande' : 'Validation en ligne bientôt disponible') + '</button>';
    h += '<div style="text-align:center"><button type="button" class="link" id="clearCart">Vider le panier</button></div>';
    foot.innerHTML = h;
  }
  function openCart() {
    $('#drCart .dr-h h2').textContent = 'Mon panier'; renderCart(); openLayer($('#drCart'));
    // relit le panier : prix, stock et mode de réception autorisé peuvent avoir changé depuis le chargement de la page
    getJson(URL_CARTGET).then(function (j) { if (j.ok && $('#drCart').classList.contains('on') && !$('#cartBody').querySelector('.done-box')) setCart(j.cart); }).catch(function () {});
  }
  function renderCheckout() {
    var c = cart; $('#drCart .dr-h h2').textContent = 'Valider ma commande';
    $('#cartBody').innerHTML = '<div class="cform">' +
      '<div><label>Réception</label><div class="modes"' + (FULFIL === 'both' ? '' : ' style="grid-template-columns:1fr"') + '>' +
        (FULFIL !== 'pickup' ? '<label class="mode"><input type="radio" name="mode" value="delivery" ' + (FULFIL === 'both' || FULFIL === 'delivery' ? 'checked' : '') + '><b>Livraison</b><span>À votre adresse</span></label>' : '') +
        (FULFIL !== 'delivery' ? '<label class="mode"><input type="radio" name="mode" value="pickup" ' + (FULFIL === 'pickup' ? 'checked' : '') + '><b>Retrait au magasin</b><span>Prêt à récupérer</span></label>' : '') +
      '</div>' + (FULFIL !== 'both' ? '<div class="mini" style="margin-top:6px">Mode de réception défini pour votre compte.</div>' : '') + '</div>' +
      '<div><label for="poRef">Votre référence de commande <span class="mini">(facultatif)</span></label><input type="text" id="poRef" maxlength="60" placeholder="Bon de commande, immatriculation, dossier…"></div>' +
      '<div><label for="oNote">Message pour notre équipe <span class="mini">(facultatif)</span></label><textarea id="oNote" rows="3" maxlength="1000" placeholder="Précisions de livraison, urgence…"></textarea></div>' +
      '<div class="ocard" style="margin:0"><div class="tot" style="margin:0"><div><span>' + c.count + ' référence' + (c.count > 1 ? 's' : '') + ', ' + c.units + ' pièce' + (c.units > 1 ? 's' : '') + '</span><span></span></div>' + (c.prices ? '<div><span>Total HT</span><b>' + fmt(c.total_ht) + ' €</b></div><div class="ttl"><span>Total TTC</span><span>' + fmt(c.total_ttc) + ' €</span></div>' : '') + '</div></div>' +
      '<div id="coErr" class="note bad" hidden></div></div>';
    $('#cartFoot').innerHTML = '<button type="button" class="cta" id="sendOrder">Envoyer la commande</button><div style="text-align:center"><button type="button" class="link" id="backCart">Retour au panier</button></div>';
  }
  function sendOrder() {
    var b = $('#sendOrder'); b.disabled = true; b.textContent = 'Envoi en cours…'; $('#coErr').hidden = true;
    var mode = $('input[name=mode]:checked').value;
    post(URL_ORDER, {mode: mode, po_ref: $('#poRef').value, note: $('#oNote').value}).then(function (j) {
      if (!j.ok) { b.disabled = false; b.textContent = 'Envoyer la commande'; var e = $('#coErr'); e.textContent = j.message || (j.errors && Object.values(j.errors)[0][0]) || 'Envoi impossible.'; e.hidden = false; return; }
      setCart(j.cart); $('#drCart .dr-h h2').textContent = 'Commande envoyée';
      $('#cartBody').innerHTML = '<div class="done-box"><div class="ok"><svg class="ic"><use href="#i-check"/></svg></div><h3>Merci, commande ' + esc(j.number) + ' envoyée</h3><p class="mini" style="margin:0 0 4px">' + (j.auto ? 'Elle est validée et part en préparation.' : 'Notre équipe la valide très rapidement.') + '</p><p class="mini" style="margin:0">Total : <b>' + fmt(j.total_ttc) + ' € TTC</b></p></div>';
      $('#cartFoot').hidden = false;
      $('#cartFoot').innerHTML = '<a class="cta" style="display:grid;place-items:center;text-decoration:none" href="' + esc(j.url) + '">Voir ma commande</a><div style="text-align:center"><button type="button" class="link js-close">Continuer mes achats</button></div>';
    }).catch(function (e) { if (e.message !== 'session') { b.disabled = false; b.textContent = 'Envoyer la commande'; var x = $('#coErr'); x.textContent = 'Envoi impossible. Vérifiez votre connexion et réessayez.'; x.hidden = false; } });
  }
  document.addEventListener('click', function (e) {
    if (e.target.id === 'checkout' && !e.target.disabled) renderCheckout();
    else if (e.target.id === 'backCart') { $('#drCart .dr-h h2').textContent = 'Mon panier'; renderCart(); }
    else if (e.target.id === 'sendOrder') sendOrder();
  });
  $('#cartBtn').addEventListener('click', openCart);
  function cartUpdate(id, qty) { post(URL_UPD, {item_id: id, qty: qty}).then(function (j) { if (j.ok) setCart(j.cart); else toast(j.message || 'Modification impossible.', {err: true}); }); }
  $('#cartBody').addEventListener('click', function (e) {
    var row = e.target.closest('.cl'); if (!row) return; var id = parseInt(row.getAttribute('data-id'), 10), inp = row.querySelector('input');
    var q = e.target.closest('[data-q]'); if (q) { cartUpdate(id, Math.max(0, (parseInt(inp.value, 10) || 0) + parseInt(q.getAttribute('data-q'), 10))); return; }
    if (e.target.closest('[data-rm]')) {
      var line = cart.lines.filter(function (l) { return l.id === id; })[0]; cartUpdate(id, 0);
      if (line) toast('Article retiré', {action: 'Annuler', run: function () { post(URL_ADD, {item_id: id, qty: line.qty}).then(function (j) { if (j.ok) setCart(j.cart); }); }});
    }
  });
  $('#cartBody').addEventListener('change', function (e) { var row = e.target.closest('.cl'); if (row && e.target.tagName === 'INPUT') cartUpdate(parseInt(row.getAttribute('data-id'), 10), Math.max(0, Math.min(9999, parseInt(e.target.value, 10) || 0))); });
  $('#cartFoot').addEventListener('click', function (e) {
    if (e.target.id === 'clearCart') { var old = cart; post(URL_CLR, {}).then(function (j) { if (j.ok) { setCart(j.cart); toast('Panier vidé', {action: 'Annuler', run: function () { old.lines.forEach(function (l) { post(URL_ADD, {item_id: l.id, qty: l.qty}).then(function (r) { if (r.ok) setCart(r.cart); }); }); }}); } }); }
  });

  /* ───────── liste de références ───────── */
  function bulkCall(mode) {
    var v = $('#bulkTxt').value.trim(); if (!v) { $('#bulkOut').innerHTML = '<p class="mini">Collez au moins une référence.</p>'; return; }
    $('#bulkOut').innerHTML = '<div class="sk"></div><div class="sk"></div>';
    post(URL_BULK, {list: v, mode: mode}).then(function (j) {
      if (!j.ok) { $('#bulkOut').innerHTML = '<p class="bad" style="color:var(--out)">' + esc(j.message || (j.errors && Object.values(j.errors)[0][0]) || 'Liste invalide.') + '</p>'; return; }
      var ok = j.lines.filter(function (l) { return l.found; }).length;
      $('#bulkOut').innerHTML = '<p class="mini" style="margin:12px 0 0"><b>' + ok + '</b> sur ' + j.lines.length + ' référence' + (j.lines.length > 1 ? 's' : '') + ' reconnue' + (ok > 1 ? 's' : '') + '.</p><table class="bl"><thead><tr><th>Saisie</th><th>Article</th><th>Qté</th><th style="text-align:right">Prix HT</th></tr></thead><tbody>' +
        j.lines.map(function (l) {
          if (!l.found) return '<tr><td style="font-family:var(--mono)">' + esc(l.input) + '</td><td class="bad" colspan="3">Référence inconnue</td></tr>';
          var r = l.row, lab = r.stock.label || (r.stock.state === 'out' ? 'Rupture' : 'Disponible');
          return '<tr><td style="font-family:var(--mono)">' + esc(l.input) + '</td><td><b style="font-family:var(--mono)">' + esc(r.code) + '</b> ' + esc(r.brand || '') + '<br><span class="mini">' + esc(r.name) + '</span>' + (l.multi ? '<br><span class="mini" style="color:var(--low)">Plusieurs articles possibles : le premier est retenu.</span>' : '') + '<br><span class="stk ' + r.stock.state + '" style="margin-top:4px"><i></i>' + esc(lab) + '</span></td><td>' + l.qty + '</td><td style="text-align:right">' + (r.price ? fmt(r.price.net_ht * l.qty) + ' €' : '') + '</td></tr>';
        }).join('') + '</tbody></table>';
      if (mode === 'add') { setCart(j.cart); toast(j.added + ' article' + (j.added > 1 ? 's' : '') + ' ajouté' + (j.added > 1 ? 's' : '') + ' au panier', {action: 'Voir le panier', run: openCart}); if (j.added) { closeAll(); openCart(); } }
    }).catch(function (e) { if (e.message !== 'session') $('#bulkOut').innerHTML = '<p style="color:var(--out)">Traitement impossible. Réessayez.</p>'; });
  }
  $('#bulkCheck').addEventListener('click', function () { bulkCall('check'); });
  $('#bulkAdd').addEventListener('click', function () { bulkCall('add'); });

  /* ───────── scanner ───────── */
  var stream = null, scanOn = false;
  function stopScan() { scanOn = false; if (stream) { stream.getTracks().forEach(function (t) { t.stop(); }); stream = null; } }
  function startScan() {
    openLayer($('#mScan')); var msg = $('#scanMsg'), vid = $('#scanVid');
    if (!('BarcodeDetector' in window) || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) { vid.hidden = true; msg.textContent = 'Le scanner n\'est pas pris en charge par ce navigateur. Saisissez le code-barres dans la recherche.'; return; }
    vid.hidden = false; msg.textContent = 'Placez le code-barres dans l\'image.';
    var det = new BarcodeDetector({formats: ['ean_13', 'ean_8', 'upc_a', 'code_128', 'code_39', 'qr_code']});
    navigator.mediaDevices.getUserMedia({video: {facingMode: 'environment'}}).then(function (s) {
      stream = s; scanOn = true; vid.srcObject = s; vid.play();
      (function tick() { if (!scanOn) return; det.detect(vid).then(function (r) { if (r && r.length) { var v = r[0].rawValue; closeAll(); setQ(v); submitSearch(); } else setTimeout(tick, 200); }).catch(function () { setTimeout(tick, 300); }); })();
    }).catch(function () { vid.hidden = true; msg.textContent = 'Accès à la caméra refusé. Autorisez-le dans votre navigateur ou saisissez le code-barres.'; });
  }

  /* ───────── démarrage ───────── */
  document.getElementById('homeLink').addEventListener('click', function (e) { e.preventDefault(); resetAll(); });
  if (window.matchMedia('(max-width:760px)').matches) $q.placeholder = 'Chercher une pièce';
  renderChips(); syncBox(); setCart(cart); $('#cartBtn').classList.remove('bump'); afterRender(true);
  typePlaceholder();
  if (location.search.indexOf('cart=1') >= 0) { history.replaceState(null, '', URL_CAT); openCart(); }
})();
</script>
@endsection
