<?php

require_once '../config/database.php';
require_once '../config/auth.php';

exigirUmDosPerfis(['tecnico', 'supervisor']);

// Recebe os filtros
$busca = trim($_GET['busca'] ?? '');
$categoria_id = filter_input(INPUT_GET, 'categoria_id', FILTER_VALIDATE_INT);

// Configuração da paginação
$por_pagina = 5;
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);

if (!$pagina || $pagina < 1) {
    $pagina = 1;
}

$inicio = ($pagina - 1) * $por_pagina;

// Busca as categorias para o filtro
$sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
$stmt = $pdo->query($sql);
$categorias = $stmt->fetchAll();

// Monta as condições da pesquisa
$condicoes = [];
$parametros = [];

if ($busca !== '') {
    $condicoes[] = "f.nome LIKE :busca";
    $parametros[':busca'] = "%$busca%";
}

if ($categoria_id) {
    $condicoes[] = "f.categoria_id = :categoria_id";
    $parametros[':categoria_id'] = $categoria_id;
}

$where = '';

if (count($condicoes) > 0) {
    $where = ' WHERE ' . implode(' AND ', $condicoes);
}

// Conta o total de ferramentas encontradas
$sql = "SELECT COUNT(*)
        FROM ferramentas f
        $where";

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$total_ferramentas = $stmt->fetchColumn();

// Calcula o total de páginas
$total_paginas = ceil($total_ferramentas / $por_pagina);

// Busca as ferramentas da página atual
$sql = "SELECT
            f.id,
            f.nome,
            c.nome AS categoria,
            f.quantidade_total,
            f.quantidade_disponivel
        FROM ferramentas f
        INNER JOIN categorias c ON f.categoria_id = c.id
        $where
        ORDER BY f.nome ASC
        LIMIT :inicio, :por_pagina";

$stmt = $pdo->prepare($sql);

// Vincula os parâmetros dos filtros
foreach ($parametros as $chave => $valor) {
    $stmt->bindValue($chave, $valor);
}

// Vincula os parâmetros da paginação como inteiros
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);

$stmt->execute();

$ferramentas = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Ferramentas</title>
</head>

<body>

    <h1>Ferramentas cadastradas</h1>

    <a href="cadastro.php">Cadastrar ferramenta</a>

    <h2>Pesquisar ferramentas</h2>

    <form method="GET">

        <label for="busca">Nome:</label>

        <input
            type="text"
            name="busca"
            id="busca"
            value="<?= htmlspecialchars($busca) ?>"
            placeholder="Digite o nome da ferramenta">

        <label for="categoria_id">Categoria:</label>

        <select name="categoria_id" id="categoria_id">

            <option value="">Todas as categorias</option>

            <?php foreach ($categorias as $categoria): ?>

                <option
                    value="<?= $categoria['id'] ?>"
                    <?= $categoria_id == $categoria['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <button type="submit">Pesquisar</button>

        <a href="listar.php">Limpar filtros</a>

    </form>

    <br>

    <p>
        Total de ferramentas encontradas: <?= $total_ferramentas ?>
    </p>

    <?php if (count($ferramentas) > 0): ?>

        <table border="1" cellpadding="8">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Quantidade total</th>
                    <th>Quantidade disponível</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($ferramentas as $ferramenta): ?>

                    <tr>
                        <td><?= $ferramenta['id'] ?></td>

                        <td>
                            <?= htmlspecialchars($ferramenta['nome']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($ferramenta['categoria']) ?>
                        </td>

                        <td><?= $ferramenta['quantidade_total'] ?></td>

                        <td><?= $ferramenta['quantidade_disponivel'] ?></td>

                        <td>
                            <a href="editar.php?id=<?= $ferramenta['id'] ?>">
                                Editar
                            </a>

                            <?php if ($_SESSION['usuario_tipo'] === 'supervisor'): ?>

                                |
                                <a href="excluir.php?id=<?= $ferramenta['id'] ?>"
                                    onclick="return confirm('Deseja realmente excluir esta ferramenta?')">
                                    Excluir
                                </a>

                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p>Nenhuma ferramenta encontrada.</p>

    <?php endif; ?>

    <br>

    <!-- Navegação entre páginas -->

    <?php if ($total_paginas > 1): ?>

        <div>

            <?php if ($pagina > 1): ?>

                <a href="?pagina=<?= $pagina - 1 ?>&busca=<?= urlencode($busca) ?>&categoria_id=<?= $categoria_id ?>">
                    Página anterior
                </a>

            <?php endif; ?>

            <span>
                Página <?= $pagina ?> de <?= $total_paginas ?>
            </span>

            <?php if ($pagina < $total_paginas): ?>

                <a href="?pagina=<?= $pagina + 1 ?>&busca=<?= urlencode($busca) ?>&categoria_id=<?= $categoria_id ?>">
                    Próxima página
                </a>

            <?php endif; ?>

        </div>

    <?php endif; ?>

    <br>

    <a href="../index.php">Voltar ao início</a>

</body>

</html>