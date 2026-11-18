<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}

$emprestimo_id = filter_input(INPUT_POST, 'emprestimo_id', FILTER_VALIDATE_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

if (!$emprestimo_id || !$quantidade || $quantidade <= 0) {
    exit('Preencha os dados corretamente.');
}

try {

    $pdo->beginTransaction();

    // Busca e bloqueia o empréstimo
    $sql = "SELECT id, ferramenta_id, quantidade
            FROM emprestimos
            WHERE id = :id
            FOR UPDATE";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $emprestimo_id]);

    $emprestimo = $stmt->fetch();

    if (!$emprestimo) {
        throw new Exception('Empréstimo não encontrado.');
    }

    // Calcula a quantidade já devolvida
    $sql = "SELECT COALESCE(SUM(quantidade), 0)
            FROM devolucoes
            WHERE emprestimo_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $emprestimo_id]);

    $quantidade_devolvida = $stmt->fetchColumn();

    // Calcula a quantidade que ainda falta devolver
    $quantidade_pendente = $emprestimo['quantidade'] - $quantidade_devolvida;

    if ($quantidade > $quantidade_pendente) {
        throw new Exception('A quantidade informada ultrapassa o saldo pendente.');
    }

    // Registra a devolução
    $sql = "INSERT INTO devolucoes
            (emprestimo_id, usuario_id, quantidade)
            VALUES
            (:emprestimo_id, :usuario_id, :quantidade)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':emprestimo_id' => $emprestimo_id,
        ':usuario_id' => $_SESSION['usuario_id'],
        ':quantidade' => $quantidade
    ]);

    // Atualiza o estoque disponível
    $sql = "UPDATE ferramentas
            SET quantidade_disponivel = quantidade_disponivel + :quantidade
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':quantidade' => $quantidade,
        ':id' => $emprestimo['ferramenta_id']
    ]);

    $pdo->commit();

    echo "Devolução registrada com sucesso!";
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo "Erro: " . htmlspecialchars($e->getMessage());
}

?>

<br><br>
<a href="listar.php">Voltar aos empréstimos</a>
<br>
<a href="../index.php">Voltar ao início</a>