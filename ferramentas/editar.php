<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();

// Recebe o ID
$id = $_GET['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    exit('Ferramenta inválida.');
}

// Busca a ferramenta
$sql = "SELECT * FROM ferramentas WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

$ferramenta = $stmt->fetch();

if (!$ferramenta) {
    exit('Ferramenta não encontrada.');
}

// Busca as categorias
$sql = "SELECT * FROM categorias ORDER BY nome ASC";

$stmt = $pdo->query($sql);
$categorias = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ferramenta</title>
</head>

<body>

    <h1>Editar Ferramenta</h1>

    <form action="atualizar.php" method="POST">

        <input type="hidden" name="id" value="<?= $ferramenta['id'] ?>">

        <label for="nome">Nome:</label>
        <input
            type="text"
            name="nome"
            id="nome"
            value="<?= htmlspecialchars($ferramenta['nome']) ?>"
            required>

        <br><br>

        <label for="categoria_id">Categoria:</label>

        <select name="categoria_id" id="categoria_id" required>

            <?php foreach ($categorias as $categoria): ?>

                <option
                    value="<?= $categoria['id'] ?>"
                    <?= $categoria['id'] == $ferramenta['categoria_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <label for="quantidade_total">Quantidade total:</label>
        <input
            type="number"
            name="quantidade_total"
            id="quantidade_total"
            value="<?= $ferramenta['quantidade_total'] ?>"
            min="1"
            required>

        <br><br>

        <p>Quantidade disponível atual: <?= $ferramenta['quantidade_disponivel'] ?></p>

        <button type="submit">Atualizar</button>

    </form>

    <br>

    <a href="listar.php">Voltar</a>

</body>

</html>