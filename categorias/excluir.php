<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirPerfil('supervisor');

// Recebe o ID
$id = $_GET['id'] ?? null;

// Valida o ID
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    exit('Categoria inválida.');
}

try {

    // Verifica se existem ferramentas vinculadas
    $sql = "SELECT COUNT(*) FROM ferramentas WHERE categoria_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $quantidade = $stmt->fetchColumn();

    if ($quantidade > 0) {
        exit('Não é possível excluir: existem ferramentas vinculadas a esta categoria.');
    }

    // Exclui a categoria
    $sql = "DELETE FROM categorias WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        exit('Categoria não encontrada.');
    }

    echo "Categoria excluída com sucesso!";
    echo "<br><a href='listar.php'>Voltar para categorias</a>";
} catch (PDOException $e) {

    exit('Erro ao excluir categoria.');
}
