<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

// Busca ferramentas com estoque disponível
$sql = "SELECT id, nome, quantidade_disponivel
        FROM ferramentas
        WHERE quantidade_disponivel > 0
        ORDER BY nome ASC";

$stmt = $pdo->query($sql);
$ferramentas = $stmt->fetchAll();

// Busca colaboradores
$sql = "SELECT id, nome, matricula
        FROM colaboradores
        ORDER BY nome ASC";

$stmt = $pdo->query($sql);
$colaboradores = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empréstimo</title>
</head>

<body>

    <h1>Registrar Empréstimo</h1>

    <form action="salvar.php" method="POST">

        <label for="ferramenta_id">Ferramenta:</label>

        <select name="ferramenta_id" id="ferramenta_id" required>

            <option value="">Selecione uma ferramenta</option>

            <?php foreach ($ferramentas as $ferramenta): ?>

                <option value="<?= $ferramenta['id'] ?>">
                    <?= htmlspecialchars($ferramenta['nome']) ?>
                    - Disponível: <?= $ferramenta['quantidade_disponivel'] ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <label for="colaborador_id">Colaborador:</label>

        <select name="colaborador_id" id="colaborador_id" required>

            <option value="">Selecione um colaborador</option>

            <?php foreach ($colaboradores as $colaborador): ?>

                <option value="<?= $colaborador['id'] ?>">
                    <?= htmlspecialchars($colaborador['nome']) ?>
                    - Matrícula: <?= htmlspecialchars($colaborador['matricula']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <label for="quantidade">Quantidade:</label>

        <input
            type="number"
            name="quantidade"
            id="quantidade"
            min="1"
            required>

        <br><br>

        <button type="submit">Registrar empréstimo</button>

    </form>

    <br>

    <a href="../index.php">Voltar ao início</a>

</body>

</html>