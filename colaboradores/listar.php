<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();

$sql = "SELECT * FROM colaboradores ORDER BY nome ASC";

$stmt = $pdo->query($sql);

$colaboradores = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Colaboradores</title>
</head>

<body>

    <h1>Colaboradores cadastrados</h1>

    <a href="cadastro.php">Novo colaborador</a>

    <br><br>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($colaboradores as $colaborador): ?>

                <tr>
                    <td><?= $colaborador['id'] ?></td>
                    <td><?= htmlspecialchars($colaborador['nome']) ?></td>
                    <td><?= htmlspecialchars($colaborador['matricula']) ?></td>

                    <td>
                        <a href="editar.php?id=<?= $colaborador['id'] ?>">Editar</a>

                        <?php if ($_SESSION['usuario_tipo'] === 'supervisor'): ?>

                            <form action="excluir.php" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Deseja realmente excluir este colaborador?')">

                                <input type="hidden" name="id" value="<?= $colaborador['id'] ?>">

                                <button type="submit">Excluir</button>

                            </form>

                        <?php endif; ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

    <br>
    <a href="../index.php">Voltar ao início</a>

</body>

</html>