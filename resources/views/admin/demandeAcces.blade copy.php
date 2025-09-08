<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des demandes d'accès</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('https://source.unsplash.com/1600x900/?technology,office');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #fff;
        }

        .container-fluid {
            max-width: 1200px;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 8px;
            margin-top: 80px;
        }

        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px;
            border-radius: 0;
        }

        nav a {
            color: #fff;
            font-weight: bold;
            margin-right: 20px;
            text-decoration: none;
        }

        nav a:hover {
            color: #007bff;
        }

        .logout-form button {
            background-color: #dc3545;
            border: none;
            font-size: 0.9rem;
        }

        .logout-form button:hover {
            background-color: #c82333;
        }

        .table th {
            background-color: #343a40;
            color: #fff;
            text-transform: uppercase;
        }

        .table-hover tbody tr:hover {
            background-color: #495057;
        }

        #messages {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<nav class="d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('admin.dashboard') }}">Tableau de Bord</a>
        <a href="{{ route('logs.index') }}">Logs</a>
        <a href="{{ route('admin.demandeAcces') }}">Demandes d'Accès</a> <!-- Lien ajouté -->
    </div>
    <div class="logout-form">
        <form action="{{ route('logout.admin') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm">Se Déconnecter</button>
        </form>
    </div>
</nav>

<div class="container-fluid">
    <h1 class="text-center mb-4">Gestion des demandes d'accès</h1>

    <!-- Affichage des messages de succès -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tableau des demandes d'accès -->
    <table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nom du client</th>
            <th>Nom du demandeur</th>
            <th>Numéro WhatsApp</th>
            <th>Déjà client</th>
            <th>Num client</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>
                <form action="{{ route('admin.mettreAJourDemande', $request->id) }}" method="POST">
                    @csrf
                    <input type="text" name="client_name" value="{{ $request->client_name }}" class="form-control" placeholder="Nom du client" />
            </td>
            <td>
                <input type="text" name="requester_name" value="{{ $request->requester_name }}" class="form-control" placeholder="Nom du demandeur" />
            </td>
            <td>
                <input type="text" name="whatsapp_number" value="{{ $request->whatsapp_number }}" class="form-control" placeholder="Numéro WhatsApp" />
            </td>
            <td>
                <!-- Affichage "Oui" ou "Non" -->
                {{ $request->is_client === 'oui' ? 'Oui' : 'Non' }}
            </td>
            <td>
                <input type="text" name="numclient" value="{{ $request->numclient }}" class="form-control" placeholder="Num client" />
            </td>
            <td>{{ ucfirst($request->status) }}</td>
            <td>
            <button type="submit" class="btn btn-primary mb-2">Mettre à jour</button>
            </form>

            <!-- Action : Approuver / Rejeter -->
            @if($request->status == 'pending')
                <form action="{{ route('admin.approuverDemande', $request->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Approuver</button>
                </form>
                <form action="{{ route('admin.rejeterDemande', $request->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </form>
            @endif
        </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
