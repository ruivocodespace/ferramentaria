<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirPerfil('supervisor');

// Recebe o ID
$id = $_GET['id'] ?? null;

// Valida o ID
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    exit('Ferramenta inválida.');
}

try {

    // Verifica se existem empréstimos vinculados
    $sql = "SELECT COUNT(*) FROM emprestimos WHERE ferramenta_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $quantidade = $stmt->fetchColumn();

    if ($quantidade > 0) {
        exit('Não é possível excluir: esta ferramenta possui histórico de empréstimos.');
    }

    // Exclui a ferramenta
    $sql = "DELETE FROM ferramentas WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        exit('Ferramenta não encontrada.');
    }

    echo "Ferramenta excluída com sucesso!";
    echo "<br><a href='listar.php'>Voltar para ferramentas</a>";
} catch (PDOException $e) {

    exit('Erro ao excluir ferramenta.');
}
