@extends('portal.layout')
@section('title', 'Devis ' . $q->number)
@section('body')
@include('portal._nav')
@php
  $m = function ($n) { return number_format($n, 2, ',', ' ') . ' €'; };
  $answered = in_array($q->status, ['answered', 'accepted'], true) || ($q->status === 'declined' && $q->answered_at);
  $showPrices = in_array($q->status, ['answered', 'accepted', 'negotiating'], true);
  $showTotals = in_array($q->status, ['answered', 'accepted'], true);
  $hasRem = $q->lines->contains(function ($l) { return (float) $l->remise > 0 && $l->availability !== 'unavailable'; });
  $canPick = $q->status === 'answered' && !$q->expired;
  $eligible = function ($l) { return $l->unit_ht !== null && $l->availability !== 'unavailable'; };
  $nElig = $q->lines->filter($eligible)->count();
  $canFiles = in_array($q->status, ['new', 'processing', 'negotiating', 'answered'], true);
  $av = ['stock' => 'En stock', 'delay' => 'Sous délai', 'unavailable' => 'Indisponible'];
@endphp
<div class="page" style="max-width:980px">
  @if(session('status'))<div class="notes"><div class="note info">{{ session('status') }}</div></div>@endif
  @if($errors->any())<div class="notes"><div class="note bad">{{ $errors->first() }}</div></div>@endif
  <a href="{{ route('portal.quotes') }}" class="mini" style="text-decoration:none">Retour à mes devis</a>
  <div class="oh">
    <div><h1 class="ph" style="margin:6px 0 2px">{{ $q->number }}</h1><span class="mini">Demandé le {{ $q->created_at->format('d/m/Y à H:i') }}@if($q->round > 1) · version {{ $q->round }}@endif</span></div>
    <span class="os st-{{ $q->status }}" style="font-size:.95rem;padding:7px 16px">{{ $q->expired ? 'Devis expiré' : $q->status_label }}</span>
  </div>

  @if(in_array($q->status, ['new', 'processing']))<div class="note info" style="margin:14px 0">Votre demande est bien reçue. Nous vous répondons avec les prix et disponibilités{{ $s->opening_hours ? ' (' . $s->opening_hours . ')' : '' }}.</div>@endif
  @if($q->status === 'negotiating')<div class="note info" style="margin:14px 0"><b>Votre demande de modification est envoyée.</b> Notre équipe vous répond avec un devis mis à jour.</div>@endif
  @if($q->status === 'answered' && !$q->expired)<div class="note info" style="margin:14px 0;background:var(--okbg);border-color:transparent;color:var(--ok)"><b>Votre devis est prêt.</b> @if($q->valid_until)Il est valable jusqu'au {{ $q->valid_until->format('d/m/Y') }}.@endif</div>@endif
  @if($q->expired)<div class="note warn" style="margin:14px 0">Ce devis a expiré le {{ $q->valid_until->format('d/m/Y') }}. @if($s->phone)Contactez-nous au {{ $s->phone }} pour le renouveler.@else Contactez-nous pour le renouveler.@endif</div>@endif
  @if($q->status === 'accepted')<div class="note info" style="margin:14px 0;background:var(--okbg);border-color:transparent;color:var(--ok)">Vous avez accepté ce devis{{ $q->decided_at ? ' le ' . $q->decided_at->format('d/m/Y à H:i') : '' }}. Notre équipe prépare votre commande.</div>@endif
  @if($q->status === 'declined')<div class="note bad" style="margin:14px 0">{{ $q->answered_at ? 'Demande refusée.' : 'Vous avez refusé ce devis.' }}@if($q->answered_at && $q->vendor_message) {{ $q->vendor_message }}@endif</div>@endif
  @if($q->vendor_message && !in_array($q->status, ['declined', 'negotiating'], true))<div class="note info" style="margin:14px 0"><b>Message de notre équipe :</b> {{ $q->vendor_message }}</div>@endif

  @if($showPrices)
  <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin:14px 0 0"><a class="btn2 pri" href="{{ route('portal.quote.pdf', ['id' => $q->id]) }}"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>Télécharger le devis (PDF)</a><a class="btn2" target="_blank" rel="noopener" href="{{ route('portal.quote.pdf', ['id' => $q->id, 'inline' => 1]) }}">Afficher</a></div>
  @endif

  <div class="ocard"><dl class="dl" style="margin:0">
    <dt>Véhicule</dt><dd>{{ $q->vehicle_text ?: '—' }}</dd>
    @if($q->vin)<dt>N° de châssis</dt><dd class="mono">{{ $q->vin }}</dd>@endif
    @if($q->note)<dt>Votre message</dt><dd style="white-space:pre-line">{{ $q->note }}</dd>@endif
    <dt>Demandé par</dt><dd>{{ optional($q->user)->name ?: '—' }}</dd>
  </dl></div>

  @if(count($files) || $canFiles)
  <div class="ocard"><b>Photos et documents</b>
    @if(count($files))<div class="qfiles">@foreach($files as $f)<a class="qfile {{ $f->by === 'vendor' ? 'v' : '' }}" href="{{ route('portal.quote.file', ['id' => $q->id, 'fileId' => $f->id]) }}" target="_blank" rel="noopener" title="{{ $f->by === 'vendor' ? 'Envoyé par notre équipe' : 'Envoyé par vous' }}">@if($f->is_image)<span class="th" style="background-image:url('{{ route('portal.quote.file', ['id' => $q->id, 'fileId' => $f->id]) }}')"></span>@else<span class="th">PDF</span>@endif<span>{{ $f->by === 'vendor' ? 'De notre équipe' : \Illuminate\Support\Str::limit($f->name, 16) }}</span></a>@endforeach</div>@else<p class="mini" style="margin:6px 0 0">Aucun fichier pour le moment.</p>@endif
    @if($canFiles)
    <form method="POST" action="{{ route('portal.quote.files', ['id' => $q->id]) }}" enctype="multipart/form-data" style="margin-top:10px;display:flex;gap:10px;flex-wrap:wrap;align-items:center" onsubmit="if(!this.files.files.length){return false}this.querySelector('button').disabled=true">@csrf
      <input type="file" name="files[]" id="addf" multiple accept="image/*,application/pdf" style="max-width:100%" required><button class="btn2" type="submit">Ajouter ces fichiers</button>
    </form>
    @endif
  </div>
  @endif

  <div class="parts" style="margin-top:14px"><table class="ot2"><thead><tr>@if($canPick)<th style="width:44px">Garder</th>@endif<th>Pièce</th><th class="r">Qté</th>@if($showPrices)<th>Disponibilité</th><th class="r">{{ $hasRem ? 'Tarif HT' : 'Prix unitaire HT' }}</th>@if($hasRem)<th class="r">Remise</th>@endif<th class="r">Total HT</th>@endif</tr></thead><tbody>
    @foreach($q->lines as $l)
      @php $refused = $q->status === 'accepted' && $l->accepted !== null && (int) $l->accepted === 0; @endphp
      <tr class="{{ $refused ? 'refused' : '' }}">
        @if($canPick)<td>@if($eligible($l))<input type="checkbox" class="qkeep" form="acceptForm" name="keep[]" value="{{ $l->id }}" data-ht="{{ $l->line_ht }}" data-ttc="{{ round($l->line_ht * (1 + $l->tva_rate / 100), 2) }}" checked aria-label="Garder cette ligne">@endif</td>@endif
        <td><b>{{ $l->designation }}</b>@if($refused) <span class="tag" style="background:#fde7e9;color:#b3202e">Refusée</span>@endif<span class="mini" style="display:block">@if($l->reference)Réf. {{ $l->reference }}@endif @if($l->brand)· {{ $l->brand }}@endif @if($showPrices && $l->vendor_ref)· proposé : <b>{{ $l->vendor_ref }}</b>@endif</span>@if($q->status === 'negotiating' && ($l->target_price !== null || $l->client_comment))<span class="mini" style="display:block;color:var(--low)">Votre souhait : @if($l->target_price !== null){{ $m($l->target_price) }} HT @endif @if($l->client_comment)« {{ $l->client_comment }} »@endif</span>@endif</td>
        <td class="r">{{ $l->qty }}</td>
        @if($showPrices)
          <td>@if($l->availability)<span class="avl {{ $l->availability }}">{{ $l->availability === 'delay' && $l->delay_text ? $l->delay_text : $av[$l->availability] }}</span>@else — @endif</td>
          <td class="r">@if($l->unit_ht !== null && $l->availability !== 'unavailable'){{ $m($l->list_ht !== null ? $l->list_ht : $l->unit_ht) }}@else — @endif</td>
          @if($hasRem)<td class="r">@if((float) $l->remise > 0 && $l->availability !== 'unavailable')-{{ rtrim(rtrim(number_format($l->remise, 2, ',', ''), '0'), ',') }} %@else — @endif</td>@endif
          <td class="r">@if($l->unit_ht !== null && $l->availability !== 'unavailable')<b>{{ $m($l->line_ht) }}</b>@else — @endif</td>
        @endif
      </tr>
    @endforeach
  </tbody></table>
  @if($q->status === 'accepted' && $q->offered_ttc !== null && abs($q->offered_ttc - $q->total_ttc) > 0.004)<p class="mini" style="margin:8px 0 0">Vous avez gardé une partie du devis : total retenu {{ $m($q->total_ttc) }} TTC (proposé : {{ $m($q->offered_ttc) }} TTC).</p>@endif
  @if($showTotals)<div class="qtot"><span>Total HT <b>{{ $m($q->total_ht) }}</b></span><span>TVA <b>{{ $m($q->total_tva) }}</b></span><span>Total TTC <b>{{ $m($q->total_ttc) }}</b></span></div>@endif
  @if($canPick && $nElig > 1)<div class="qsel" id="qsel" aria-live="polite"></div>@endif
  </div>

  @if($q->status === 'answered' && !$q->expired)
    <div class="qact">
      <form method="POST" id="acceptForm" action="{{ route('portal.quote.accept', ['id' => $q->id]) }}" onsubmit="if(!document.querySelector('.qkeep:checked')&&document.querySelector('.qkeep')){alert('Cochez au moins une ligne à garder, ou refusez le devis.');return false}this.querySelector('button').disabled=true">@csrf<button class="btn2 pri" type="submit" id="acceptBtn">Accepter le devis</button></form>
      <a class="btn2" href="#renego" onclick="document.getElementById('renego').open=true">Demander une modification</a>
      <form method="POST" action="{{ route('portal.quote.decline', ['id' => $q->id]) }}" onsubmit="return confirm('Refuser ce devis ?')">@csrf<button class="btn2" type="submit">Refuser</button></form>
    </div>
    <details id="renego" class="ocard" style="margin-top:14px"><summary style="cursor:pointer;font-weight:700">Un prix ou une quantité à revoir ?</summary>
      <form method="POST" action="{{ route('portal.quote.negotiate', ['id' => $q->id]) }}" class="qform" onsubmit="this.querySelector('button[type=submit]').disabled=true">@csrf
        <p class="mini" style="margin:10px 0">Pour chaque pièce, indiquez la quantité ou le prix HT unitaire que vous souhaitez. Laissez vide ce qui ne change pas. Nous vous renvoyons un devis mis à jour.</p>
        <div class="parts"><table class="ot2"><thead><tr><th>Pièce</th><th class="r">Prix actuel HT</th><th>Quantité</th><th>Prix souhaité HT</th><th>Commentaire</th></tr></thead><tbody>
        @foreach($q->lines as $l)
          @if($l->availability !== 'unavailable' && $l->unit_ht !== null)
          <tr><td><b>{{ $l->designation }}</b></td><td class="r">{{ $m($l->unit_ht) }}</td>
            <td><input type="number" min="1" max="9999" name="lines[{{ $l->id }}][qty]" value="{{ $l->qty }}" style="width:80px"></td>
            <td><input type="number" min="0" step="0.01" name="lines[{{ $l->id }}][target]" placeholder="ex. 45,00" style="width:110px"></td>
            <td><input type="text" maxlength="255" name="lines[{{ $l->id }}][comment]" placeholder="facultatif" style="width:100%;min-width:140px"></td></tr>
          @endif
        @endforeach
        </tbody></table></div>
        <label class="l" for="nmsg">Message pour notre équipe</label>
        <textarea name="message" id="nmsg" rows="2" maxlength="1000" placeholder="Ex. Pouvez-vous faire un effort sur le total si je prends tout ?"></textarea>
        <div class="qact"><button class="btn2 pri" type="submit">Envoyer ma demande de modification</button></div>
      </form>
    </details>
  @elseif(in_array($q->status, ['new', 'processing', 'negotiating']))
    <div class="qact"><form method="POST" action="{{ route('portal.quote.cancel', ['id' => $q->id]) }}" onsubmit="return confirm('Annuler cette demande ?')">@csrf<button class="btn2" type="submit">Annuler la demande</button></form></div>
  @endif
  @if($q->events->count())
  <div class="ocard" style="margin-top:14px"><b>Historique</b>
    <div class="evl">
    @foreach($q->events->reverse() as $e)
      <div class="ev {{ $e->actor }}"><span class="dot"></span><div><b>{{ \App\Models\PortalQuoteEvent::LABELS[$e->type] ?? $e->type }}</b> <span class="mini">{{ $e->created_at->format('d/m/Y H:i') }}{{ $e->who && $e->actor === 'client' ? ' · ' . $e->who : '' }}</span>@if($e->message)<div class="mini" style="white-space:pre-line;color:var(--ink2)">{{ $e->message }}</div>@endif</div></div>
    @endforeach
    </div>
  </div>
  @endif
</div>
@if($canPick && $nElig > 1)
<script>
(function(){
  var boxes=[].slice.call(document.querySelectorAll('.qkeep')),out=document.getElementById('qsel'),btn=document.getElementById('acceptBtn');
  function eur(n){return n.toLocaleString('fr-FR',{minimumFractionDigits:2,maximumFractionDigits:2})+' €';}
  function upd(){var n=0,ht=0,ttc=0;boxes.forEach(function(b){if(b.checked){n++;ht+=parseFloat(b.dataset.ht)||0;ttc+=parseFloat(b.dataset.ttc)||0;}});
    out.innerHTML=n===boxes.length?'Vous gardez <b>toutes les lignes</b>. Décochez celles que vous ne voulez pas.':(n?'Vous gardez <b>'+n+' ligne'+(n>1?'s':'')+' sur '+boxes.length+'</b> : total <b>'+eur(ht)+' HT</b> · <b>'+eur(ttc)+' TTC</b>':'Aucune ligne gardée.');
    btn.textContent=n===boxes.length?'Accepter le devis':(n?'Accepter la sélection ('+n+')':'Accepter le devis');}
  boxes.forEach(function(b){b.addEventListener('change',upd);});upd();
})();
</script>
@endif
@endsection
