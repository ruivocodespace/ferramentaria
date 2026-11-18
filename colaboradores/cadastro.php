<?php
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Colaboradores</title>
</head>

<body>

    <h1>Cadastro de Colaborador</h1>

    <form action="salvar.php" method="POST">

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <br><br>

        <label for="matricula">Matrícula:</label>
        <input type="text" name="matricula" id="matricula" required>

        <br><br>

        <button type="submit">Cadastrar</button>

    </form>

    <br>
    <a href="listar.php">Ver colaboradores</a>
    <br>
    <a href="../index.php">Voltar ao início</a>

</body>

</html>