<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();

// Verifica se o formulário foi enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}

// Recebe os dados
$nome = trim($_POST['nome'] ?? '');
$categoria_id = $_POST['categoria_id'] ?? '';
$quantidade_total = $_POST['quantidade_total'] ?? '';

// Valida os campos
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

    // Verifica se a categoria existe
    $sql = "SELECT id FROM categorias WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $categoria_id]);

    if (!$stmt->fetch()) {
        exit('Categoria não encontrada.');
    }

    // Define a quantidade disponível
    $quantidade_disponivel = $quantidade_total;

    // Insere a ferramenta
    $sql = "INSERT INTO ferramentas
            (nome, categoria_id, quantidade_total, quantidade_disponivel)
            VALUES (:nome, :categoria_id, :quantidade_total, :quantidade_disponivel)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':categoria_id' => $categoria_id,
        ':quantidade_total' => $quantidade_total,
        ':quantidade_disponivel' => $quantidade_disponivel
    ]);

    echo "Ferramenta cadastrada com sucesso!";
    echo "<br><a href='cadastro.php'>Cadastrar outra</a>";
    echo "<br><a href='../index.php'>Voltar ao início</a>";
} catch (PDOException $e) {

    exit('Erro ao cadastrar ferramenta.');
}
