<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel AuraPark</title>
</head>
<body>
    <h1>Bem-vindo(a) ao Painel de Administração do AuraPark!</h1>
    <p>Esta é a rota principal (/). O login funcionou!</p>
    <p>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Deslogar (Sair)
        </a>
    </p>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>