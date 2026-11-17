
<?php
require_once '../config/database.php';

// Verifica se o formulário foi enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Método inválido.");
}

// Recebe os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Valida os campos
if (empty($nome) || empty($email) || empty($senha)) {
    exit("Preencha todos os campos.");
}
// Valida o email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('E-mail inválido.');
}

if (strlen($senha) < 8) {
    exit('A senha deve possuir pelo menos 8 caracteres.');
}

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Define o perfil padrão (técnico)
$tipo = 'tecnico';

try {

    // Consulta SQL
    $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
            VALUES (:nome, :email, :senha, :tipo)";

    $stmt = $pdo->prepare($sql);

    // Executa a inserção
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':tipo' => $tipo
    ]);

    echo "Usuário cadastrado com sucesso!";
    echo "<br><a href='cadastro.php'>Cadastrar outro usuário</a>";
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        exit('Este e-mail já está cadastrado.');
    }

    exit('Erro ao cadastrar usuário.');
}
