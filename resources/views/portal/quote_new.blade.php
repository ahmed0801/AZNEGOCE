@extends('portal.layout')
@section('title', 'Demande de devis')
@section('body')
@include('portal._nav')
@php
  $rows = old('lines');
  if (!$rows) $rows = count($pre) ? $pre : [['reference' => '', 'designation' => '', 'brand' => '', 'qty' => 1]];
  $rows = array_values($rows);
@endphp
<div class="page" style="max-width:980px">
  <a href="{{ route('portal.quotes') }}" class="mini" style="text-decoration:none">Retour à mes devis</a>
  <h1 class="ph" style="margin-top:6px">Demande de devis</h1>
  <form method="POST" action="{{ route('portal.quote.store') }}" class="qform" id="qf" enctype="multipart/form-data">
    @csrf
    <div class="sec">
      <h2>Véhicule</h2>
      <p class="hint">Facultatif, mais il nous aide à trouver les bonnes pièces. Saisissez la plaque d'immatriculation ou décrivez le véhicule.</p>
      @if(count($vehicles))
        <label class="l" for="vehicle_id">Un de vos véhicules</label>
        <select name="vehicle_id" id="vehicle_id"><option value="">Un autre véhicule (saisie ci-dessous)</option>@foreach($vehicles as $v)<option value="{{ $v['id'] }}" {{ (string) old('vehicle_id') === (string) $v['id'] ? 'selected' : '' }}>{{ $v['label'] }}</option>@endforeach</select>
      @endif
      <div id="vnew">
      <label class="l" for="plate">Plaque d'immatriculation</label>
      <div class="plate-row"><input type="text" name="plate" id="plate" maxlength="12" autocomplete="off" autocapitalize="characters" placeholder="AB-123-CD" value="{{ old('plate') }}" aria-describedby="plateRes"><button type="button" class="btn2 pri" id="plateGo">Identifier</button></div>
      <div id="plateRes" class="plate-res" aria-live="polite"></div>
      <div class="two" id="vfree">
        <div><label class="l" for="vehicle_text">Plaque ou description</label><input type="text" name="vehicle_text" id="vehicle_text" maxlength="255" value="{{ old('vehicle_text') }}" placeholder="AB-123-CD ou Golf 7 1.6 TDI 2015"></div>
        <div><label class="l" for="vin">N° de châssis (VIN), si vous l'avez</label><input type="text" name="vin" id="vin" maxlength="30" value="{{ old('vin') }}" placeholder="17 caractères, sur la carte grise (E)"></div>
      </div>
      </div>
    </div>
    <div class="sec">
      <h2>Pièces demandées</h2>
      <p class="hint">Une ligne par pièce. Référence ou désignation suffisent ; la pièce peut ne pas exister dans notre catalogue.</p>
      <details id="paste" style="margin:0 0 12px"><summary class="btn2" style="display:inline-flex;cursor:pointer">Coller une liste (Excel, e-mail…)</summary>
        <div style="margin-top:10px"><textarea id="pasteTa" rows="5" placeholder="Une pièce par ligne : référence, quantité, désignation facultative.&#10;0986424797 2&#10;Disque de frein avant ; 1&#10;BD-12&#9;4&#9;Disque Brembo" style="width:100%;border:1.5px solid var(--line);border-radius:11px;padding:10px 12px;background:var(--ground)"></textarea>
        <div style="display:flex;gap:10px;align-items:center;margin-top:8px"><button type="button" class="btn2 pri" id="pasteGo">Ajouter ces pièces</button><span class="mini" id="pasteInfo"></span></div></div>
      </details>
      <div class="qhead"><span>Référence</span><span>Désignation</span><span>Marque souhaitée</span><span>Qté</span><span></span></div>
      <div id="qrows">
      @foreach($rows as $i => $l)
        <div class="qrow"><input type="text" name="lines[{{ $i }}][reference]" maxlength="80" placeholder="Référence" value="{{ $l['reference'] ?? '' }}"><input type="text" name="lines[{{ $i }}][designation]" maxlength="255" placeholder="Ex. plaquettes de frein avant" value="{{ $l['designation'] ?? '' }}"><input type="text" name="lines[{{ $i }}][brand]" maxlength="120" placeholder="Marque" value="{{ $l['brand'] ?? '' }}"><input type="number" name="lines[{{ $i }}][qty]" min="1" max="9999" value="{{ $l['qty'] ?? 1 }}"><button type="button" class="rm" aria-label="Retirer cette pièce">&times;</button></div>
      @endforeach
      </div>
      <button type="button" class="btn2" id="addRow">Ajouter une pièce</button>
    </div>
    <div class="sec">
      <h2>Photos et documents</h2>
      <p class="hint">Facultatif : photo de la pièce, de l'étiquette, de la carte grise… Cela évite les erreurs de référence. Photos (JPG, PNG, WebP) ou PDF, 8 fichiers maximum.</p>
      <label class="drop" id="drop" for="qfiles"><svg class="ic sm" viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg><span><b>Ajouter des photos ou des fichiers</b><small>Touchez ici (appareil photo ou galerie sur mobile) ou glissez-déposez</small></span></label>
      <input type="file" name="files[]" id="qfiles" multiple accept="image/*,application/pdf" style="position:absolute;width:1px;height:1px;opacity:0">
      <div id="fprev" class="fprev"></div>
    </div>
    <div class="sec">
      <h2>Message</h2>
      <label class="l" for="note" style="margin-top:0">Précisions pour notre équipe (facultatif)</label>
      <textarea name="note" id="note" rows="3" maxlength="2000" placeholder="Urgence, côté gauche/droit, qualité d'origine ou économique…">{{ old('note') }}</textarea>
    </div>
    @if($errors->any())<div class="errs"><div class="note bad">{{ $errors->first() }}</div></div>@endif
    <div class="qact"><button type="submit" class="btn2 pri" id="qsend">Envoyer la demande</button><a class="btn2" href="{{ route('portal.quotes') }}">Annuler</a></div>
  </form>
</div>
<script>
(function(){
  var box=document.getElementById('qrows'),n=box.children.length;
  function mk(){var d=document.createElement('div');d.className='qrow';d.innerHTML='<input type="text" name="lines['+n+'][reference]" maxlength="80" placeholder="Référence"><input type="text" name="lines['+n+'][designation]" maxlength="255" placeholder="Ex. plaquettes de frein avant"><input type="text" name="lines['+n+'][brand]" maxlength="120" placeholder="Marque"><input type="number" name="lines['+n+'][qty]" min="1" max="9999" value="1"><button type="button" class="rm" aria-label="Retirer cette pièce">&times;</button>';n++;return d}
  document.getElementById('addRow').addEventListener('click',function(){if(box.children.length>=40)return;var r=mk();box.appendChild(r);r.querySelector('input').focus()});
  box.addEventListener('click',function(e){var b=e.target.closest('.rm');if(!b)return;if(box.children.length>1)b.parentNode.remove();else b.parentNode.querySelectorAll('input[type=text]').forEach(function(i){i.value=''})});
  // « coller une liste » : tabulation / point-virgule / espaces ; quantité = dernier nombre entier de la ligne
  function parseLine(t){
    t=t.trim(); if(!t)return null;
    var parts=t.split(/[\t;]+/).map(function(x){return x.trim();}).filter(Boolean), ref='',des='',qty=1;
    if(parts.length===1){
      var m=parts[0].match(/^(.*\S)\s+(?:x\s*)?(\d{1,4})$/i);
      if(m){parts[0]=m[1];qty=parseInt(m[2],10);}
      if(/\s/.test(parts[0]))des=parts[0];else ref=parts[0];
    }else{
      var rest=[];
      parts.forEach(function(p,i){ if(/^\d{1,4}$/.test(p)&&i>0&&qty===1&&!parts._q){qty=parseInt(p,10);parts._q=true;}else rest.push(p); });
      ref=rest[0]||''; des=rest.slice(1).join(' ');
      if(/\s/.test(ref)&&!des){des=ref;ref='';}
    }
    return {reference:ref,designation:des,qty:Math.max(1,qty)};
  }
  document.getElementById('pasteGo').addEventListener('click',function(){
    var lines=document.getElementById('pasteTa').value.split(/\r?\n/).map(parseLine).filter(Boolean), added=0;
    lines.forEach(function(l){
      if(box.children.length>=40)return;
      var first=box.children[0], empty=box.children.length===1&&!first.querySelector('input[name$="[reference]"]').value&&!first.querySelector('input[name$="[designation]"]').value;
      var r=empty?first:mk(); if(!empty)box.appendChild(r);
      var ins=r.querySelectorAll('input'); ins[0].value=l.reference; ins[1].value=l.designation; ins[3].value=l.qty; added++;
    });
    document.getElementById('pasteInfo').textContent=added?added+' pièce(s) ajoutée(s). Vérifiez les lignes ci-dessous.':'Rien à ajouter.';
    if(added){document.getElementById('pasteTa').value='';}
  });
  var sel=document.getElementById('vehicle_id'),free=document.getElementById('vnew');
  function vis(){if(sel)free.style.display=sel.value?'none':'';}
  // ── plaque : identification automatique (marque, modèle, motorisation) ──
  var plate=document.getElementById('plate'),pres=document.getElementById('plateRes'),pgo=document.getElementById('plateGo'),lastP='';
  function say(t,cls){pres.className='plate-res '+(cls||'');pres.textContent=t||'';}
  function lookup(){
    var raw=plate.value.replace(/[^A-Za-z0-9]/g,'').toUpperCase();
    if(raw.length<4){say('Saisissez la plaque complète (ex. AB-123-CD).','bad');return;}
    if(raw===lastP)return; lastP=raw; pgo.disabled=true; say('Identification du véhicule…','');
    var fd=new FormData();fd.append('plate',raw);fd.append('_token',document.querySelector('#qf input[name=_token]').value);
    fetch({!! json_encode(route('portal.quote.plate', [], false)) !!},{method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},body:fd})
      .then(function(r){return r.json().catch(function(){return {};}).then(function(j){j.__ok=r.ok;return j;});})
      .then(function(j){
        pgo.disabled=false;
        var vt=document.getElementById('vehicle_text'),vin=document.getElementById('vin');
        if(j.ok){plate.value=j.plate;say('Véhicule identifié : '+j.label,'ok');vt.value=(j.plate+' '+j.label).trim();if(j.vin&&!vin.value)vin.value=j.vin;}
        else{lastP='';say(j.message||'Identification impossible. Décrivez le véhicule ci-dessous.','bad');if(!vt.value&&j.plate)vt.value=j.plate;}
      }).catch(function(){pgo.disabled=false;lastP='';say('Identification indisponible pour le moment. Décrivez le véhicule ci-dessous.','bad');});
  }
  pgo.addEventListener('click',lookup);
  plate.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();lookup();}});
  plate.addEventListener('input',function(){plate.value=plate.value.toUpperCase();if(/^[A-Z]{2}[\s-]?\d{3}[\s-]?[A-Z]{2}$/.test(plate.value.trim()))lookup();});
  // ── photos : aperçu, réduction automatique des grosses photos de téléphone ──
  var fin=document.getElementById('qfiles'),fprev=document.getElementById('fprev'),drop=document.getElementById('drop'),held=[];
  function sync(){var dt=new DataTransfer();held.forEach(function(f){dt.items.add(f);});fin.files=dt.files;
    fprev.innerHTML='';held.forEach(function(f,i){var d=document.createElement('div');d.className='fp';
      if(f.type.indexOf('image/')===0){var im=document.createElement('img');im.src=URL.createObjectURL(f);im.alt='';d.appendChild(im);}else{var s=document.createElement('span');s.textContent='PDF';d.appendChild(s);}
      var b=document.createElement('button');b.type='button';b.setAttribute('aria-label','Retirer');b.textContent='×';b.onclick=function(){held.splice(i,1);sync();};d.appendChild(b);fprev.appendChild(d);});}
  function shrink(f){return new Promise(function(ok){
    if(f.type.indexOf('image/')!==0||f.type==='image/gif'||f.size<900000){ok(f);return;}
    var img=new Image(),u=URL.createObjectURL(f);
    img.onload=function(){var m=1600,k=Math.min(1,m/Math.max(img.width,img.height)),c=document.createElement('canvas');c.width=Math.round(img.width*k);c.height=Math.round(img.height*k);c.getContext('2d').drawImage(img,0,0,c.width,c.height);URL.revokeObjectURL(u);
      c.toBlob(function(bl){ok(bl?new File([bl],(f.name.replace(/\.[^.]+$/,'')||'photo')+'.jpg',{type:'image/jpeg'}):f);},'image/jpeg',0.82);};
    img.onerror=function(){URL.revokeObjectURL(u);ok(f);};img.src=u;});}
  function addFiles(list){Promise.all([].slice.call(list).filter(function(f){return /^image\//.test(f.type)||f.type==='application/pdf';}).map(shrink)).then(function(fs){fs.forEach(function(f){if(held.length<8)held.push(f);});sync();});}
  fin.addEventListener('change',function(){addFiles(fin.files);});
  ['dragover','dragenter'].forEach(function(ev){drop.addEventListener(ev,function(e){e.preventDefault();drop.classList.add('on');});});
  ['dragleave','drop'].forEach(function(ev){drop.addEventListener(ev,function(e){e.preventDefault();drop.classList.remove('on');if(ev==='drop'&&e.dataTransfer)addFiles(e.dataTransfer.files);});});
  if(sel){sel.addEventListener('change',vis);vis();}
  document.getElementById('qf').addEventListener('submit',function(){var b=document.getElementById('qsend');b.disabled=true;b.textContent='Envoi en cours…';});
})();
</script>
@endsection
