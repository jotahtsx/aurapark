<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - AuraPark</title>
</head>
<body>
    <h1>Tela de Login</h1>
    <p>O Laravel encontrou a view com sucesso!</p>

    <form method="POST" action="/login">
        @csrf
        <label>Email:</label>
        <input type="email" name="email">
        <br>
        <label>Senha:</label>
        <input type="password" name="password">
        <br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>