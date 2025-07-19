<div class="container mt-5">
    <h1 class="mb-4">Carrinho</h1>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Variação</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Subtotal</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($carrinho)): ?>
                <?php foreach ($carrinho as $index => $item): ?>
                    <tr>
                        <td><?= $item["nome"] ?></td>
                        <td><?= $item["variacao"] ?></td>
                        <td><?= $item["quantidade"] ?></td>
                        <td>R$ <?= number_format($item["preco"], 2, ",", ".") ?></td>
                        <td>R$ <?= number_format($item["preco"] * $item["quantidade"], 2, ",", ".") ?></td>
                        <td>
                            <a href="<?= site_url("carrinho/remover/" . $index) ?>" class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Carrinho vazio.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Frete: R$ <?= number_format($frete, 2, ",", ".") ?></h3>

    <form method="post" action="<?= site_url('pedidos/checkout') ?>" id="checkoutForm">
        <div class="mb-3">
            <label for="cliente_nome" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" id="cliente_nome" name="cliente_nome" placeholder="Digite seu nome" value="<?= $cliente_nome ?>" required>
        </div>
        <div class="mb-3">
            <label for="cliente_email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="cliente_email" name="cliente_email" placeholder="Digite seu e-mail" value="<?= $cliente_email ?>" required>
        </div>
        <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP" value="<?= $cep ?>" required>
        </div>
        <div class="mb-3">
            <label for="cupom" class="form-label">Cupom</label>
            <select class="form-control" id="cupom" name="cupom">
                <option value="">Nenhum</option>
                <?php foreach ($cupons as $cupom): ?>
                    <option value="<?= $cupom["codigo"] ?>" <?= $cupom["codigo"] == $cupom_selecionado ? 'selected' : '' ?>>
                        <?= $cupom["codigo"] ?> - <?= $cupom["desconto"] ?>% (Mínimo: R$ <?= number_format($cupom["valor_minimo"], 2, ",", ".") ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Criar Pedido</button>
    </form>
</div>

<script>
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        const emailField = document.getElementById('cliente_email');
        const emailValue = emailField.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(emailValue)) {
            e.preventDefault();
            alert('Por favor, insira um e-mail válido.');
            return;
        }

        const nomeField = document.getElementById('cliente_nome');
        const nomeValue = nomeField.value.trim();
        if (nomeValue.length < 3) {
            e.preventDefault();
            alert('O nome deve ter no mínimo 3 letras.');
            return;
        }
    });

    document.getElementById('cep').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.slice(0, 8);
        value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });
</script>