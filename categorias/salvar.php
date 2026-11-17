<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirLogin();

// Verifica se o formulário foi enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}

// Recebe o nome da categoria
$nome = trim($_POST['nome'] ?? '');

// Valida o campo
if (empty($nome)) {
    exit('Informe o nome da categoria.');
}

try {

    // Prepara a consulta SQL
    $sql = "INSERT INTO categorias (nome) VALUES (:nome)";

    $stmt = $pdo->prepare($sql);

    // Executa a inserção
    $stmt->execute([
        ':nome' => $nome
    ]);

    echo "Categoria cadastrada com sucesso!";
    echo "<br><a href='cadastro.php'>Cadastrar outra</a>";
    echo "<br><a href='../index.php'>Voltar ao início</a>";
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        exit('Esta categoria já está cadastrada.');
    }

    exit('Erro ao cadastrar categoria.');
}
