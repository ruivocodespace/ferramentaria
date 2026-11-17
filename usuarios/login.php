<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>

    <h1>Login - Ferramentaria</h1>

    <form action="autenticar.php" method="POST">

        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>

        <br><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <br><br>

        <button type="submit">Entrar</button>

    </form>

</body>

</html>