<div class="container mt-5">
    <h1 class="mb-4"><?= isset($cupom) ? 'Editar Cupom' : 'Criar Cupom' ?></h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" value="<?= isset($cupom) ? $cupom['codigo'] : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="desconto" class="form-label">Desconto</label>
            <input type="number" step="0.01" class="form-control" id="desconto" name="desconto" value="<?= isset($cupom) ? $cupom['desconto'] : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="validade" class="form-label">Validade</label>
            <input type="date" class="form-control" id="validade" name="validade" value="<?= isset($cupom) ? $cupom['validade'] : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="valor_minimo" class="form-label">Valor Mínimo</label>
            <input type="number" step="0.01" class="form-control" id="valor_minimo" name="valor_minimo" value="<?= isset($cupom) ? $cupom['valor_minimo'] : '' ?>" required>
        </div>
        <button type="submit" class="btn btn-success"><?= isset($cupom) ? 'Atualizar' : 'Salvar' ?></button>
        <a href="<?= site_url('cupons') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>