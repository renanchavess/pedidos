<div class="container mt-5">
    <h1 class="mb-4">Finalizar Pedido</h1>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('pedidos/checkout') ?>" id="checkoutForm">
        <div class="mb-3">
            <label for="cliente_nome" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" id="cliente_nome" name="cliente_nome" placeholder="Digite seu nome" value="<?= set_value('cliente_nome') ?>" required>
        </div>
        <div class="mb-3">
            <label for="cliente_email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="cliente_email" name="cliente_email" placeholder="Digite seu e-mail" value="<?= set_value('cliente_email') ?>" required>
        </div>
        <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP" value="<?= set_value('cep') ?>" required>
        </div>
        <div class="mb-3">
            <label for="cupom" class="form-label">Cupom</label>
            <select class="form-control" id="cupom" name="cupom">
                <option value="">Nenhum</option>
                <?php foreach ($cupons as $cupom): ?>
                    <option value="<?= $cupom["codigo"] ?>" <?= set_select('cupom', $cupom["codigo"]) ?>>
                        <?= $cupom["codigo"] ?> - <?= $cupom["desconto"] ?>% (Mínimo: R$ <?= number_format($cupom["valor_minimo"], 2, ",", ".") ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Finalizar Pedido</button>
    </form>
</div>