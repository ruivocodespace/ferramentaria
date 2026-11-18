<?php

require_once 'config/database.php';
require_once 'config/auth.php';

exigirLogin();

$user_name = $_SESSION['usuario_nome'];
$user_tipo = $_SESSION['usuario_tipo'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ferramentaria</title>
</head>

<body>

    <header>
        <h1>Sistema de Ferramentaria</h1>

        <p>
            Bem-vindo, <?= htmlspecialchars($user_name) ?>!
        </p>

        <p>
            Perfil: <?= htmlspecialchars(ucfirst($user_tipo)) ?>
        </p>

        <a href="usuarios/logout.php">Sair do sistema</a>
    </header>

    <hr>

    <nav>
        <h2>Menu de navegação</h2>

        <h3>Cadastros</h3>

        <ul>
            <li><a href="categorias/cadastro.php">Cadastrar categoria</a></li>
            <li><a href="categorias/listar.php">Listar categorias</a></li>

            <li><a href="ferramentas/cadastro.php">Cadastrar ferramenta</a></li>
            <li><a href="ferramentas/listar.php">Listar ferramentas</a></li>

            <li><a href="colaboradores/cadastro.php">Cadastrar colaborador</a></li>
            <li><a href="colaboradores/listar.php">Listar colaboradores</a></li>
        </ul>

        <h3>Movimentações</h3>

        <ul>
            <li><a href="emprestimos/cadastro.php">Registrar empréstimo</a></li>
            <li><a href="emprestimos/listar.php">Consultar empréstimos</a></li>
        </ul>

    </nav>

    <hr>

    <footer>
        <p>Sistema de Controle de Ferramentaria</p>
    </footer>

</body>

</html>