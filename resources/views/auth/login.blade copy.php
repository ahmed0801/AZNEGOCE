<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - PREMA GROS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-image: url('{{ asset('assets/img/backlogin.webp') }}');
            background-size: 100% 100%; /* Assure que l'image couvre l'intégralité */
            background-repeat: no-repeat;
            background-position: center;
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            background-attachment: fixed;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            transform: translateY(50px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            font-family: 'Open Sans', sans-serif;
            margin-bottom: 30px;
        }

        .logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 180px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 8px;
            padding: 15px;
            width: 100%;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 86, 179, 0.5);
            outline: none;
        }

        .btn {
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            font-size: 18px;
            border-radius: 8px;
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
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-group input {
            font-size: 16px;
            padding-left: 10px;
        }

        /* Effet sur les champs du formulaire */
        .form-group input, .form-group textarea {
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #0056b3;
            box-shadow: 0 0 5px rgba(0, 86, 179, 0.3);
        }

    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('assets/img/logop.png') }}" alt="PREMA GROS Logo" class="logo">
        <h2>Espace Clients</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control" required>
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
