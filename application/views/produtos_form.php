<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4"><?= isset($produto) ? 'Gerenciar Produto' : 'Adicionar Produto' ?></h1>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12 col-md-7">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= isset($produto) ? $produto['nome'] : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço</label>
                        <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?= isset($produto) ? $produto['preco'] : '' ?>" required>
                    </div>
                    <div id="variacoes" class="row">
                        <h3>Variações</h3>
                        <?php if (isset($variacoes) && !empty($variacoes)): ?>
                            <?php foreach ($variacoes as $index => $variacao): ?>
                                <div class="variacao col-12 col-md-4 mb-3" id="variacao_<?= $index ?>">
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="hidden" name="variacoes[<?= $index ?>][id]" value="<?= $variacao['id'] ?>">
                                            <label for="variacoes_<?= $index ?>_nome" class="form-label">Nome da Variação</label>
                                            <input type="text" class="form-control" id="variacoes_<?= $index ?>_nome" name="variacoes[<?= $index ?>][nome]" value="<?= $variacao['variacao'] ?>" required>
                                            <label for="variacoes_<?= $index ?>_quantidade" class="form-label">Quantidade</label>
                                            <input type="number" step="0.01" class="form-control" id="variacoes_<?= $index ?>_quantidade" name="variacoes[<?= $index ?>][quantidade]" value="<?= $variacao['quantidade'] ?>" required>
                                            <button type="button" class="btn btn-danger mt-2" onclick="removerVariacao('variacao_<?= $index ?>')">Remover</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="variacao col-12 col-md-4 mb-3" id="variacao_0">
                                <div class="card">
                                    <div class="card-body">
                                        <label for="variacoes_0_nome" class="form-label">Nome da Variação</label>
                                        <input type="text" class="form-control" id="variacoes_0_nome" name="variacoes[0][nome]" required>
                                        <label for="variacoes_0_quantidade" class="form-label">Quantidade</label>
                                        <input type="number" step="0.01" class="form-control" id="variacoes_0_quantidade" name="variacoes[0][quantidade]" required>
                                        <button type="button" class="btn btn-danger mt-2" onclick="removerVariacao('variacao_0')">Remover</button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="adicionarVariacao()">Adicionar Variação</button>
                    <button type="submit" class="btn btn-success"><?= isset($produto) ? 'Atualizar' : 'Salvar' ?></button>
                    <a href="<?= site_url('produtos') ?>" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>

            <div class="col-12 col-md-5">
                <?php if (isset($produto)): ?>
                    <div class="card">
                        <div class="card-body">
                            <h3>Comprar Produto</h3>
                            <form method="post" action="<?= site_url('carrinho/adicionar/' . $produto['id']) ?>">
                                <div class="mb-3">
                                    <label for="variacao_id" class="form-label">Selecione a Variação</label>
                                    <select class="form-control" id="variacao_id" name="variacao_id" required>
                                        <option value="">Escolha uma variação</option>
                                        <?php foreach ($variacoes as $variacao): ?>
                                            <option value="<?= $variacao['id'] ?>"><?= $variacao['variacao'] ?> (Estoque: <?= $variacao['quantidade'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="quantidade" class="form-label">Quantidade</label>
                                    <input type="number" class="form-control" id="quantidade" name="quantidade" value="1" min="1" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function adicionarVariacao() {
            const variacoesDiv = document.getElementById('variacoes');
            const index = variacoesDiv.querySelectorAll('.variacao').length;
            const novaVariacao = `
                <div class="variacao col-12 col-md-4 mb-3" id="variacao_${index}">
                    <div class="card">
                        <div class="card-body">
                            <label for="variacoes_${index}_nome" class="form-label">Nome da Variação</label>
                            <input type="text" class="form-control" id="variacoes_${index}_nome" name="variacoes[${index}][nome]" required>
                            <label for="variacoes_${index}_quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="variacoes_${index}_quantidade" name="variacoes[${index}][quantidade]" required>
                            <button type="button" class="btn btn-danger mt-2" onclick="removerVariacao('variacao_${index}')">Remover</button>
                        </div>
                    </div>
                </div>
            `;
            variacoesDiv.insertAdjacentHTML('beforeend', novaVariacao);
        }

        function removerVariacao(id) {
            const variacaoDiv = document.getElementById(id);
            if (variacaoDiv) {
                variacaoDiv.remove();
            }
        }
    </script>
</body>
</html>