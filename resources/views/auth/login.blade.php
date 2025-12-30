<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DESTOCK ERP</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-image: url('{{ asset('assets/img/back.webp') }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 30px;
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 150px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
        }
        .btn {
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #003f88;
        }
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('assets/img/logop.png') }}" alt="PREMA GROS Logo" class="logo">
        <h2>Espace Commercial</h2>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        

        <form action="{{ route('login.admin') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn mt-3">Se connecter</button>
        </form>
    </div>
</body>
</html>
