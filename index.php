<?php
require_once 'config/auth.php';

exigirLogin();
?>

<h1>Sistema de Ferramentaria</h1>
<p>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</p>

<a href="usuarios/logout.php">Sair</a>