<div class="container mt-5">
    <h1 class="mb-4">Lista de Pedidos</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>E-mail</th>
                <th>Subtotal</th>
                <th>Frete</th>
                <th>Total</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pedidos)): ?>
                <tr><td colspan="9" class="text-center">Nenhum pedido encontrado.</td></tr>
            <?php else: ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= $pedido["id"] ?></td>
                        <td><?= $pedido["cliente_nome"] ?></td>
                        <td><?= $pedido["cliente_email"] ?></td>
                        <td>R$ <?= number_format($pedido["subtotal"], 2, ",", ".") ?></td>
                        <td>R$ <?= number_format($pedido["frete"], 2, ",", ".") ?></td>
                        <td>R$ <?= number_format($pedido["total"], 2, ",", ".") ?></td>
                        <td>
                            <?php if ($pedido["status"] === "pendente"): ?>
                                <span class="badge bg-warning text-dark">Pendente</span>
                            <?php elseif ($pedido["status"] === "pago"): ?>
                                <span class="badge bg-success">Pago</span>
                            <?php elseif ($pedido["status"] === "cancelado"): ?>
                                <span class="badge bg-danger">Cancelado</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido["criado_em"])) ?></td>
                        <td>
                            <a href="<?= site_url("pedidos/detalhes/" . $pedido["id"]) ?>" class="btn btn-info btn-sm">Detalhes</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>