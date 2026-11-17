<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();
// Consulta as categorias cadastradas
$sql = "SELECT * FROM categorias ORDER BY nome ASC";

$stmt = $pdo->query($sql);

$categorias = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Categorias</title>
</head>

<body>

    <h1>Categorias cadastradas</h1>

    <a href="cadastro.php">Nova categoria</a>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($categorias as $categoria): ?>

                <tr>
                    <td><?= $categoria['id'] ?></td>
                    <td><?= htmlspecialchars($categoria['nome']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $categoria['id'] ?>">Editar</a>
                        <a href="excluir.php?id=<?= $categoria['id'] ?>"
                            onclick="return confirm('Deseja realmente excluir esta categoria?')">
                            Excluir
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

    <br>

    <a href="../index.php">Voltar ao início</a>

</body>

</html>