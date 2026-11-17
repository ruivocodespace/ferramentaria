<?php
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Categoria</title>
</head>

<body>

    <h1>Cadastro de Categoria</h1>

    <form action="salvar.php" method="POST">

        <label for="nome">Nome da categoria:</label>
        <input type="text" name="nome" id="nome" required>

        <button type="submit">Cadastrar</button>

    </form>

    <a href="../index.php">Voltar</a>

</body>

</html>