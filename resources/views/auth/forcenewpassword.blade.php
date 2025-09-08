<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier votre mot de passe</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-center">Modifier Votre Mot de Passe</h3>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form action="{{ route('force.new.password.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nouveau mot de passe (Minimum 8 Caractères)</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                    @error('new_password')
            <div class="text-danger">{{ $message }}</div> <!-- Affichage du message d'erreur -->
        @enderror
                </div>
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirmez le Nouveau mot de passe</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
