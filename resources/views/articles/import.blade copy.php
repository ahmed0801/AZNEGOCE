<div class="container">
    <h1>Importer des articles depuis Excel</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('articles.import.template') }}" class="btn btn-primary mb-3">Télécharger le modèle Excel</a>

    <form id="importForm" action="{{ route('articles.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Choisir le fichier Excel :</label>
            <input type="file" name="file" id="fileInput" class="form-control" accept=".xlsx,.xls,.csv" required>
        </div>

        <div id="preview" class="mt-3 table-responsive"></div>

        <button type="submit" class="btn btn-success mt-2">Importer</button>
    </form>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const preview = document.getElementById('preview');
    preview.innerHTML = `<p>📄 Fichier sélectionné : <strong>${file.name}</strong> (${(file.size/1024).toFixed(2)} KB)</p>`;

    // Prévisualisation avec fetch
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("articles.import.preview") }}', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        let html = '<table class="table table-bordered table-sm">';
        html += '<thead><tr>';
        for (const key of Object.keys(data.preview[0])) {
            html += `<th>${key}</th>`;
        }
        html += '</tr></thead><tbody>';
        for (const row of data.preview) {
            html += '<tr>';
            for (const value of Object.values(row)) {
                html += `<td>${value}</td>`;
            }
            html += '</tr>';
        }
        html += '</tbody></table>';

        if(data.errors.length) {
            html += '<div class="alert alert-danger"><ul>';
            for(const err of data.errors) {
                html += `<li>${err}</li>`;
            }
            html += '</ul></div>';
        }

        preview.innerHTML += html;
    });
});
</script>
