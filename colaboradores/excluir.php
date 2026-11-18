<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirPerfil('supervisor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}

$id = $_POST['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
    exit('Colaborador inválido.');
}

try {

    // Verifica se existem empréstimos vinculados
    $sql = "SELECT COUNT(*) FROM emprestimos WHERE colaborador_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $quantidade = $stmt->fetchColumn();

    if ($quantidade > 0) {
        exit('Não é possível excluir: este colaborador possui histórico de empréstimos.');
    }

    // Exclui o colaborador
    $sql = "DELETE FROM colaboradores WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        exit('Colaborador não encontrado.');
    }

    echo "Colaborador excluído com sucesso!";
    echo "<br><a href='listar.php'>Voltar para colaboradores</a>";
} catch (PDOException $e) {

    exit('Erro ao excluir colaborador.');
}
