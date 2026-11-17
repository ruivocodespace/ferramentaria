<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

// Busca as categorias cadastradas
$sql = "SELECT * FROM categorias ORDER BY nome ASC";

$stmt = $pdo->query($sql);

$categorias = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Ferramentas</title>
</head>

<body>

    <h1>Cadastro de Ferramentas</h1>

    <form action="salvar.php" method="POST">

        <label for="nome">Nome da ferramenta:</label>
        <input type="text" name="nome" id="nome" required>

        <br><br>

        <label for="categoria_id">Categoria:</label>

        <select name="categoria_id" id="categoria_id" required>
            <option value="">Selecione uma categoria</option>

            <?php foreach ($categorias as $categoria): ?>

                <option value="<?= $categoria['id'] ?>">
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <label for="quantidade_total">Quantidade total:</label>
        <input type="number" name="quantidade_total" id="quantidade_total" min="1" required>

        <br><br>

        <button type="submit">Cadastrar ferramenta</button>

    </form>

    <br>

    <a href="../index.php">Voltar</a>

</body>

</html>