<div class="container mt-5">
    <h1 class="mb-4">Lista de Cupons</h1>
    <a href="<?= site_url('cupons/criar') ?>" class="btn btn-primary mb-3">Adicionar Cupom</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Desconto</th>
                <th>Validade</th>
                <th>Valor Mínimo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cupons)): ?>
                <tr><td colspan="6" class="text-center">Nenhum cupom encontrado.</td></tr>
            <?php else: ?>
                <?php foreach ($cupons as $cupom): ?>
                    <tr>
                        <td><?= $cupom["id"] ?></td>
                        <td><?= $cupom["codigo"] ?></td>
                        <td>R$ <?= number_format($cupom["desconto"], 2, ",", ".") ?></td>
                        <td><?= date('d/m/Y', strtotime($cupom["validade"])) ?></td>
                        <td>R$ <?= number_format($cupom["valor_minimo"], 2, ",", ".") ?></td>
                        <td>
                            <a href="<?= site_url("cupons/editar/" . $cupom["id"]) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= site_url("cupons/excluir/" . $cupom["id"]) ?>" class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>