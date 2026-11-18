<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit('Empréstimo inválido.');
}

// Busca os dados do empréstimo e calcula o saldo pendente
$sql = "SELECT
            e.id,
            f.nome AS ferramenta,
            c.nome AS colaborador,
            e.quantidade,
            COALESCE(SUM(d.quantidade), 0) AS quantidade_devolvida,
            e.quantidade - COALESCE(SUM(d.quantidade), 0) AS quantidade_pendente
        FROM emprestimos e
        INNER JOIN ferramentas f ON e.ferramenta_id = f.id
        INNER JOIN colaboradores c ON e.colaborador_id = c.id
        LEFT JOIN devolucoes d ON e.id = d.emprestimo_id
        WHERE e.id = :id
        GROUP BY e.id, f.nome, c.nome, e.quantidade";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

$emprestimo = $stmt->fetch();

if (!$emprestimo) {
    exit('Empréstimo não encontrado.');
}

if ($emprestimo['quantidade_pendente'] <= 0) {
    exit('Este empréstimo já foi totalmente devolvido.');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Devolução</title>
</head>

<body>

    <h1>Registrar Devolução</h1>

    <p>Ferramenta: <?= htmlspecialchars($emprestimo['ferramenta']) ?></p>

    <p>Colaborador: <?= htmlspecialchars($emprestimo['colaborador']) ?></p>

    <p>Quantidade emprestada: <?= $emprestimo['quantidade'] ?></p>

    <p>Quantidade devolvida: <?= $emprestimo['quantidade_devolvida'] ?></p>

    <p>Quantidade pendente: <?= $emprestimo['quantidade_pendente'] ?></p>

    <form action="salvar_devolucao.php" method="POST">

        <input type="hidden" name="emprestimo_id" value="<?= $emprestimo['id'] ?>">

        <label for="quantidade">Quantidade a devolver:</label>

        <input
            type="number"
            name="quantidade"
            id="quantidade"
            min="1"
            max="<?= $emprestimo['quantidade_pendente'] ?>"
            required>

        <br><br>

        <button type="submit">Registrar devolução</button>

    </form>

    <br>

    <a href="listar.php">Voltar aos empréstimos</a>

</body>

</html>