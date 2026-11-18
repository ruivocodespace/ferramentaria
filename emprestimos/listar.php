<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

// Busca os empréstimos e calcula as quantidades devolvidas e pendentes
$sql = "SELECT
            e.id,
            f.nome AS ferramenta,
            c.nome AS colaborador,
            c.matricula,
            u.nome AS responsavel,
            e.quantidade,
            COALESCE(SUM(d.quantidade), 0) AS quantidade_devolvida,
            e.quantidade - COALESCE(SUM(d.quantidade), 0) AS quantidade_pendente,
            e.data_emprestimo
        FROM emprestimos e
        INNER JOIN ferramentas f ON e.ferramenta_id = f.id
        INNER JOIN colaboradores c ON e.colaborador_id = c.id
        INNER JOIN usuarios u ON e.usuario_id = u.id
        LEFT JOIN devolucoes d ON e.id = d.emprestimo_id
        GROUP BY
            e.id,
            f.nome,
            c.nome,
            c.matricula,
            u.nome,
            e.quantidade,
            e.data_emprestimo
        ORDER BY e.data_emprestimo DESC";

$stmt = $pdo->query($sql);
$emprestimos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos</title>
</head>

<body>

    <h1>Empréstimos registrados</h1>

    <a href="cadastro.php">Registrar empréstimo</a>

    <br><br>

    <?php if (count($emprestimos) > 0): ?>

        <table border="1" cellpadding="8">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ferramenta</th>
                    <th>Colaborador</th>
                    <th>Matrícula</th>
                    <th>Quantidade</th>
                    <th>Devolvida</th>
                    <th>Pendente</th>
                    <th>Responsável pelo registro</th>
                    <th>Data do empréstimo</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($emprestimos as $emprestimo): ?>

                    <tr>
                        <td><?= $emprestimo['id'] ?></td>

                        <td>
                            <?= htmlspecialchars($emprestimo['ferramenta']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($emprestimo['colaborador']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($emprestimo['matricula']) ?>
                        </td>

                        <td><?= $emprestimo['quantidade'] ?></td>

                        <td><?= $emprestimo['quantidade_devolvida'] ?></td>

                        <td><?= $emprestimo['quantidade_pendente'] ?></td>

                        <td>
                            <?= htmlspecialchars($emprestimo['responsavel']) ?>
                        </td>

                        <td>
                            <?= date('d/m/Y H:i', strtotime($emprestimo['data_emprestimo'])) ?>
                        </td>

                        <td>
                            <?php if ($emprestimo['quantidade_pendente'] > 0): ?>

                                <a href="devolver.php?id=<?= $emprestimo['id'] ?>">
                                    Devolver
                                </a>

                            <?php else: ?>

                                Devolvido

                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p>Nenhum empréstimo registrado.</p>

    <?php endif; ?>

    <br>

    <a href="../index.php">Voltar ao início</a>

</body>

</html>