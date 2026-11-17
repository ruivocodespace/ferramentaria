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

// Valida os dados
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    exit('Categoria inválida.');
}

if (empty($nome)) {
    exit('Informe o nome da categoria.');
}

try {

    // Atualiza a categoria
    $sql = "UPDATE categorias SET nome = :nome WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':id' => $id
    ]);

    echo "Categoria atualizada com sucesso!";
    echo "<br><a href='listar.php'>Voltar para categorias</a>";
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        exit('Já existe uma categoria com esse nome.');
    }

    exit('Erro ao atualizar categoria.');
}
