@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form id="modify-password-form" action="{{ route('modify.password') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="old_password">Ancien mot de passe</label>
        <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Entrez votre ancien mot de passe" required>
    </div>

    <div class="form-group">
        <label for="new_password">Nouveau mot de passe</label>
        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Entrez votre nouveau mot de passe" required>
    </div>

    <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
</form>

<script>
    document.getElementById('modify-password-form').addEventListener('submit', function () {
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerText = 'Traitement en cours...';
    });
</script>
