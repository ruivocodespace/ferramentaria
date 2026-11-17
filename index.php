<?php
require_once 'config/auth.php';

exigirLogin();

$user_name = $_SESSION['usuario_nome'];
$user_tipo = $_SESSION['usuario_tipo'];

?>

<h1>Sistema de Ferramentaria</h1>
<p>Bem-vindo, <?= htmlspecialchars($user_name) ?>!</p>
<p>Perfil: <?= htmlspecialchars($user_tipo) ?></p>

<a href="usuarios/logout.php">Sair</a>