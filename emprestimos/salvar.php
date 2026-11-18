<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Método inválido.');
}

// Recebe os dados
$ferramenta_id = filter_input(INPUT_POST, 'ferramenta_id', FILTER_VALIDATE_INT);
$colaborador_id = filter_input(INPUT_POST, 'colaborador_id', FILTER_VALIDATE_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

// Valida os dados
if (!$ferramenta_id || !$colaborador_id || !$quantidade || $quantidade <= 0) {
    exit('Preencha todos os campos corretamente.');
}

try {

    // Inicia a transação dos dados
    $pdo->beginTransaction();

    // Busca a ferramenta durante a operação
    $sql = "SELECT quantidade_disponivel
            FROM ferramentas
            WHERE id = :id
            FOR UPDATE";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $ferramenta_id]);

    $ferramenta = $stmt->fetch();

    if (!$ferramenta) {
        throw new Exception('Ferramenta não encontrada.');
    }

    // Verifica se existe estoque suficiente
    if ($quantidade > $ferramenta['quantidade_disponivel']) {
        throw new Exception('Quantidade indisponível no estoque.');
    }

    // Verifica se o colaborador existe
    $sql = "SELECT id FROM colaboradores WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $colaborador_id]);

    if (!$stmt->fetch()) {
        throw new Exception('Colaborador não encontrado.');
    }

    // Registra o empréstimo
    $sql = "INSERT INTO emprestimos
            (ferramenta_id, colaborador_id, usuario_id, quantidade)
            VALUES
            (:ferramenta_id, :colaborador_id, :usuario_id, :quantidade)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':ferramenta_id' => $ferramenta_id,
        ':colaborador_id' => $colaborador_id,
        ':usuario_id' => $_SESSION['usuario_id'],
        ':quantidade' => $quantidade
    ]);

    // Atualiza o estoque disponível
    $sql = "UPDATE ferramentas
            SET quantidade_disponivel = quantidade_disponivel - :quantidade
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':quantidade' => $quantidade,
        ':id' => $ferramenta_id
    ]);

    // Confirma as operações
    $pdo->commit();

    echo "Empréstimo registrado com sucesso!";
} catch (Exception $e) {

    // Desfaz as operações em caso de erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo "Erro: " . htmlspecialchars($e->getMessage());
}

?>

<br><br>
<a href="cadastro.php">Registrar outro empréstimo</a>
<br>
<a href="../index.php">Voltar ao início</a>