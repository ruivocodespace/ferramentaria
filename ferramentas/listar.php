<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();

// Consulta ferramentas e suas respectivas categorias
$sql = "SELECT
            ferramentas.id,
            ferramentas.nome,
            categorias.nome AS categoria,
            ferramentas.quantidade_total,
            ferramentas.quantidade_disponivel
        FROM ferramentas
        INNER JOIN categorias
            ON ferramentas.categoria_id = categorias.id
        ORDER BY ferramentas.nome ASC";

$stmt = $pdo->query($sql);

$ferramentas = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Ferramentas</title>
</head>

<body>

    <h1>Ferramentas cadastradas</h1>

    <a href="cadastro.php">Nova ferramenta</a>

    <br><br>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Quantidade total</th>
                <th>Quantidade disponível</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($ferramentas as $ferramenta): ?>

                <tr>
                    <td><?= $ferramenta['id'] ?></td>
                    <td><?= htmlspecialchars($ferramenta['nome']) ?></td>
                    <td><?= htmlspecialchars($ferramenta['categoria']) ?></td>
                    <td><?= $ferramenta['quantidade_total'] ?></td>
                    <td><?= $ferramenta['quantidade_disponivel'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $ferramenta['id'] ?>">Editar</a>
                        <a href="excluir.php?id=<?= $ferramenta['id'] ?>"
                            onclick="return confirm('Deseja realmente excluir esta ferramenta?')">
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