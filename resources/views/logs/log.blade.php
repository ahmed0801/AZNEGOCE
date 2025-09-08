<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Authentifications</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            margin-top: 50px;
        }

        nav {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px;
            border-radius: 8px;
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
    </style>
</head>
<body>

<!-- Navbar -->
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
    <h1 class="mb-4 text-center">Historique des Authentifications</h1>

    <!-- Formulaire de filtre -->
    <form method="GET" action="{{ route('logs.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="client" class="form-control" placeholder="Numéro Client" value="{{ request('client') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Nom Client" value="{{ request('name') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Tableau des logs -->
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Numéro Client</th>
                <th>Nom Client</th>
                <th>Date de Connexion</th>
                <th>Lieu de Connexion</th>
                <th>Région Client</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->CustomerNo }}</td>
                    <td>{{ $log->CustomerName }}</td>
                    <td>{{ $log->login_date }}</td>
                    <td>{{ $log->lieu_de_connexion }}</td>
                    <td>{{ $log->region }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun log trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    @if(method_exists($logs, 'links'))
        <div class="d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
    @endif
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
