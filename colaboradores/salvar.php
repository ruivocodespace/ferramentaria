<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}

$nome = trim($_POST['nome'] ?? '');
$matricula = trim($_POST['matricula'] ?? '');

if (empty($nome) || empty($matricula)) {
    exit('Preencha todos os campos.');
}

try {

    $sql = "INSERT INTO colaboradores (nome, matricula)
            VALUES (:nome, :matricula)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':matricula' => $matricula
    ]);

    echo "Colaborador cadastrado com sucesso!";
    echo "<br><a href='cadastro.php'>Cadastrar outro</a>";
    echo "<br><a href='listar.php'>Ver colaboradores</a>";
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        exit('Esta matrícula já está cadastrada.');
    }

    exit('Erro ao cadastrar colaborador.');
}
