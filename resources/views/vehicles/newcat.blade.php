@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Recherche véhicule par plaque</h1>

    <div id="alert" style="display:none;" class="alert"></div>

    <form id="plateForm">
        @csrf
        <div class="mb-3">
            <label for="plate" class="form-label">Immatriculation</label>
            <input type="text" class="form-control" id="plate" name="plate" placeholder="ex: CP-322-CX">
        </div>
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>

    <hr>

    <div id="loading" style="display:none;">Chargement…</div>

    <div id="vehicleInfo" style="margin-top:20px; display:none;">
        <h3>Infos véhicule (API plaque)</h3>
        <ul id="vehicleList"></ul>
    </div>

    <div id="tecdocInfo" style="margin-top:20px; display:none;">
        <h3>Résultat TecDoc</h3>
        <p>LinkageTargetId: <span id="linkageTargetId"></span></p>
        <p>Articles trouvés: <span id="articlesCount"></span></p>

        <table id="articlesTable" class="table table-striped" style="display:none;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Article Number</th>
                    <th>Short Description</th>
                    <th>Manufacturer</th>
                    <th>ArticleId</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('plateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const plate = document.getElementById('plate').value.trim();
    const alertEl = document.getElementById('alert');
    alertEl.style.display = 'none';

    if (!plate) {
        alertEl.className = 'alert alert-danger';
        alertEl.textContent = 'Veuillez saisir une immatriculation.';
        alertEl.style.display = 'block';
        return;
    }

    document.getElementById('loading').style.display = 'block';
    document.getElementById('vehicleInfo').style.display = 'none';
    document.getElementById('tecdocInfo').style.display = 'none';
    document.getElementById('articlesTable').style.display = 'none';
    document.querySelector('#articlesTable tbody').innerHTML = '';

    try {
        const token = document.querySelector('input[name=_token]').value;
        const res = await fetch("{{ route('vehicle.catalog.fetch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ plate: plate })
        });

        const json = await res.json();

        document.getElementById('loading').style.display = 'none';

        if (!res.ok || !json.success) {
            alertEl.className = 'alert alert-danger';
            alertEl.textContent = json.message || 'Erreur lors de la requête';
            alertEl.style.display = 'block';
            console.error(json);
            return;
        }

        // Affiche infos plaque
        const info = json.plate_info || {};
        const vehicleList = document.getElementById('vehicleList');
        vehicleList.innerHTML = '';
        for (const key of ['immat','marque','modele','version','date1erCir_fr','vin']) {
            if (info[key]) {
                const li = document.createElement('li');
                li.textContent = key + ': ' + info[key];
                vehicleList.appendChild(li);
            }
        }
        document.getElementById('vehicleInfo').style.display = 'block';

        // TecDoc
        document.getElementById('linkageTargetId').textContent = json.linkageTargetId || '';
        document.getElementById('articlesCount').textContent = json.articles_count || 0;
        document.getElementById('tecdocInfo').style.display = 'block';

        const articles = json.articles || [];
        if (articles.length > 0) {
            const tbody = document.querySelector('#articlesTable tbody');
            articles.forEach((a, idx) => {
                const tr = document.createElement('tr');
                const number = a['articleNumber'] ?? a['articleId'] ?? a['articleNo'] ?? '';
                const shortDesc = a['shortDescription'] ?? a['articleShortDescription'] ?? a['articleDescription'] ?? '';
                const mfr = a['mfrName'] ?? a['manufacturerName'] ?? (a['manufacturer']?.name ?? '');

                tr.innerHTML = `<td>${idx+1}</td>
                                <td>${number}</td>
                                <td>${shortDesc}</td>
                                <td>${mfr}</td>
                                <td>${a['articleId'] ?? ''}</td>`;
                tbody.appendChild(tr);
            });
            document.getElementById('articlesTable').style.display = 'table';
        } else {
            // aucun article
            const tbody = document.querySelector('#articlesTable tbody');
            tbody.innerHTML = '<tr><td colspan="5">Aucun article trouvé pour ce véhicule.</td></tr>';
            document.getElementById('articlesTable').style.display = 'table';
        }

    } catch (err) {
        document.getElementById('loading').style.display = 'none';
        alertEl.className = 'alert alert-danger';
        alertEl.textContent = 'Erreur JavaScript / réseau : ' + err.message;
        alertEl.style.display = 'block';
        console.error(err);
    }
});
</script>
@endsection
