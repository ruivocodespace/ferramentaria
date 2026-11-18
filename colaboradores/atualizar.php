
<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$matricula = trim($_POST['matricula'] ?? '');

if (!$id || !filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
    exit('Colaborador inválido.');
}

if (empty($nome) || empty($matricula)) {
    exit('Preencha todos os campos.');
}

try {

    $sql = "UPDATE colaboradores
            SET nome = :nome, matricula = :matricula
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':matricula' => $matricula,
        ':id' => $id
    ]);

    if ($stmt->rowCount() === 0) {

        $sql = "SELECT id FROM colaboradores WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        if (!$stmt->fetch()) {
            exit('Colaborador não encontrado.');
        }
    }

    echo "Colaborador atualizado com sucesso!";
    echo "<br><a href='listar.php'>Voltar para colaboradores</a>";
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        exit('Esta matrícula já pertence a outro colaborador.');
    }

    exit('Erro ao atualizar colaborador.');
}
