<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

$id = $_GET['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
    exit('Colaborador inválido.');
}

$sql = "SELECT * FROM colaboradores WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

$colaborador = $stmt->fetch();

if (!$colaborador) {
    exit('Colaborador não encontrado.');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Colaborador</title>
</head>

<body>

    <h1>Editar Colaborador</h1>

    <form action="atualizar.php" method="POST">

        <input type="hidden" name="id" value="<?= $colaborador['id'] ?>">

        <label for="nome">Nome:</label>
        <input
            type="text"
            name="nome"
            id="nome"
            value="<?= htmlspecialchars($colaborador['nome']) ?>"
            required>

        <br><br>

        <label for="matricula">Matrícula:</label>
        <input
            type="text"
            name="matricula"
            id="matricula"
            value="<?= htmlspecialchars($colaborador['matricula']) ?>"
            required>

        <br><br>

        <button type="submit">Atualizar</button>

    </form>

    <br>
    <a href="listar.php">Voltar</a>

</body>

</html>