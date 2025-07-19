<div class="container mt-5">
    <h1 class="mb-4">Detalhes do Pedido #<?= $pedido['id'] ?></h1>

    <div class="mb-3">
        <h3>Informações do Cliente</h3>
        <p><strong>Nome:</strong> <?= $pedido['cliente_nome'] ?></p>
        <p><strong>E-mail:</strong> <?= $pedido['cliente_email'] ?></p>
        <p><strong>Endereço:</strong> <?= $pedido['endereco'] ?></p>
        <p><strong>CEP:</strong> <?= $pedido['cep'] ?></p>
    </div>

    <div class="mb-3">
        <h3>Resumo do Pedido</h3>
        <p><strong>Subtotal:</strong> R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></p>
        <p><strong>Frete:</strong> R$ <?= number_format($pedido['frete'], 2, ',', '.') ?></p>
        <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
        <p><strong>Status:</strong> <?= ucfirst($pedido['status']) ?></p>
        <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['criado_em'])) ?></p>
    </div>

    <div class="mb-3">
        <h3>Itens do Pedido</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variação</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= $item['produto'] ?></td>
                        <td><?= $item['variacao'] ?: 'N/A' ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="<?= site_url('pedidos') ?>" class="btn btn-secondary">Voltar</a>
</div>