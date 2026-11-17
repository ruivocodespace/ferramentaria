<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}

// Recebe os dados
$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$categoria_id = $_POST['categoria_id'] ?? '';
$quantidade_total = $_POST['quantidade_total'] ?? '';

// Valida os dados
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    exit('Ferramenta inválida.');
}

if (empty($nome) || empty($categoria_id) || $quantidade_total === '') {
    exit('Preencha todos os campos.');
}

if (
    !filter_var($categoria_id, FILTER_VALIDATE_INT) ||
    !filter_var($quantidade_total, FILTER_VALIDATE_INT) ||
    $quantidade_total <= 0
) {
    exit('Dados inválidos.');
}

try {

    $pdo->beginTransaction();

    // Busca a ferramenta atual
    $sql = "SELECT * FROM ferramentas WHERE id = :id FOR UPDATE";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $ferramenta = $stmt->fetch();

    if (!$ferramenta) {
        $pdo->rollBack();
        exit('Ferramenta não encontrada.');
    }

    // Calcula a quantidade emprestada
    $quantidade_emprestada =
        $ferramenta['quantidade_total'] -
        $ferramenta['quantidade_disponivel'];

    // Verifica se o novo total comporta as ferramentas emprestadas
    if ($quantidade_total < $quantidade_emprestada) {
        $pdo->rollBack();
        exit('A quantidade total não pode ser menor que a quantidade emprestada.');
    }

    // Calcula a nova quantidade disponível
    $quantidade_disponivel =
        $quantidade_total - $quantidade_emprestada;

    // Atualiza a ferramenta
    $sql = "UPDATE ferramentas
            SET nome = :nome,
                categoria_id = :categoria_id,
                quantidade_total = :quantidade_total,
                quantidade_disponivel = :quantidade_disponivel
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':categoria_id' => $categoria_id,
        ':quantidade_total' => $quantidade_total,
        ':quantidade_disponivel' => $quantidade_disponivel,
        ':id' => $id
    ]);

    $pdo->commit();

    echo "Ferramenta atualizada com sucesso!";
    echo "<br><a href='listar.php'>Voltar para ferramentas</a>";
} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    exit('Erro ao atualizar ferramenta.');
}
