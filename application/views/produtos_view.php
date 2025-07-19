<div class="container mt-5">
    <h1 class="mb-4">Lista de Produtos</h1>
    <a href="<?= site_url('produtos/criar') ?>" class="btn btn-primary mb-3">Adicionar Produto</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Variações</th>
                <th>Quantidade Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($produtos)): ?>
                <tr><td colspan="6" class="text-center">Nenhum produto encontrado.</td></tr>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= $produto["id"] ?></td>
                        <td><?= $produto["nome"] ?></td>
                        <td>R$ <?= number_format($produto["preco"], 2, ",", ".") ?></td>
                        <td><?= $produto["variacoes"] ?: "N/A" ?></td>
                        <td><?= $produto["quantidade"] ?></td>
                        <td>
                            <a href="<?= site_url("produtos/editar/" . $produto["id"]) ?>" class="btn btn-warning btn-sm">Gerenciar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>