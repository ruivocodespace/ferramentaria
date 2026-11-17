<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();

// Recebe o ID pela URL
$id = $_GET['id'] ?? null;

// Verifica se o ID foi informado
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    exit('Categoria inválida.');
}

// Busca a categoria no banco
$sql = "SELECT * FROM categorias WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

$categoria = $stmt->fetch();

// Verifica se a categoria existe
if (!$categoria) {
    exit('Categoria não encontrada.');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
</head>

<body>

    <h1>Editar Categoria</h1>

    <form action="atualizar.php" method="POST">

        <input type="hidden" name="id" value="<?= $categoria['id'] ?>">

        <label for="nome">Nome da categoria:</label>

        <input
            type="text"
            name="nome"
            id="nome"
            value="<?= htmlspecialchars($categoria['nome']) ?>"
            required>

        <button type="submit">Atualizar</button>

    </form>

    <a href="listar.php">Voltar</a>

</body>

</html>