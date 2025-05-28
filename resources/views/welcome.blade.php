<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a QuizApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #6fc0da 0%, #2256c2 100%);
        color: #333;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .hero {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        max-width: 900px;
        width: 100%;
        text-align: center;
        animation: fadeIn 1.2s ease-in-out;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: 700;
        color: #6fc0da;
        margin-bottom: 1rem;
    }

    .hero p {
        font-size: 1.2rem;
        color: #666;
    }

    .hero .btn-start {
        margin-top: 2rem;
        background: #2256c2;
        color: white;
        font-weight: bold;
        border: none;
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
        border-radius: 10px;
        transition: 0.3s;
    }

    .hero .btn-start:hover {
        background: #6fc0da;
    }

    .quiz-animation {
        animation: float 3s ease-in-out infinite;
        max-width: 200px;
        margin: 0 auto 2rem;
    }

    @keyframes float {
        0% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    </style>
</head>
<body>
    <div class="hero">
        <img src="{{ asset('assets/img/logo-ct-white.png') }}" alt="Logo"  class="mb-0">
        <h1>¡Bienvenido a QuizApp!</h1>
        <p>Desafía tu mente, demuestra lo que sabes y aprende de forma divertida. Nuestra plataforma te ofrece quizzes interactivos, resultados inmediatos y una experiencia única.</p>
        @auth
            <a href="{{ route('quizzes.index') }}" class="btn btn-start">Ir a mis Quizzes</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-start">Comenzar ahora</a>
        @endauth
    </div>
</body>
</html>
