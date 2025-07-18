<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function adicionarVariacao() {
            const variacoesDiv = document.getElementById('variacoes');
            const index = variacoesDiv.children.length / 2; // Cada variação tem 2 campos
            const novaVariacao = `
                <div class="mb-3">
                    <label for="variacoes_${index}_nome" class="form-label">Nome da Variação</label>
                    <input type="text" class="form-control" id="variacoes_${index}_nome" name="variacoes[${index}][nome]" required>
                </div>
                <div class="mb-3">
                    <label for="variacoes_${index}_quantidade" class="form-label">Quantidade</label>
                    <input type="number" class="form-control" id="variacoes_${index}_quantidade" name="variacoes[${index}][quantidade]" required>
                </div>
            `;
            variacoesDiv.insertAdjacentHTML('beforeend', novaVariacao);
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Coluna para editar o produto -->
            <div class="col-lg-6">
                <h1 class="mb-4"><?= isset($produto) ? 'Editar Produto' : 'Adicionar Produto' ?></h1>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= isset($produto) ? $produto['nome'] : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço</label>
                        <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?= isset($produto) ? $produto['preco'] : '' ?>" required>
                    </div>
                    <div id="variacoes">
                        <h3>Variações</h3>
                        <?php if (isset($variacoes) && !empty($variacoes)): ?>
                            <?php foreach ($variacoes as $index => $variacao): ?>
                                <div class="mb-3">
                                    <label for="variacoes_<?= $index ?>_nome" class="form-label">Nome da Variação</label>
                                    <input type="text" class="form-control" id="variacoes_<?= $index ?>_nome" name="variacoes[<?= $variacao['id'] ?>][nome]" value="<?= $variacao['variacao'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="variacoes_<?= $index ?>_quantidade" class="form-label">Quantidade</label>
                                    <input type="number" class="form-control" id="variacoes_<?= $index ?>_quantidade" name="variacoes[<?= $variacao['id'] ?>][quantidade]" value="<?= $variacao['quantidade'] ?>" required>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="mb-3">
                                <label for="variacoes_0_nome" class="form-label">Nome da Variação</label>
                                <input type="text" class="form-control" id="variacoes_0_nome" name="variacoes[0][nome]" required>
                            </div>
                            <div class="mb-3">
                                <label for="variacoes_0_quantidade" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="variacoes_0_quantidade" name="variacoes[0][quantidade]" required>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="adicionarVariacao()">Adicionar Variação</button>
                    <button type="submit" class="btn btn-success"><?= isset($produto) ? 'Atualizar' : 'Salvar' ?></button>
                    <a href="<?= site_url('produtos') ?>" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>

            <!-- Coluna para adicionar ao carrinho -->
            <div class="col-lg-6">
                <h1 class="mb-4">Adicionar ao Carrinho</h1>
                <?php if (isset($produto) && !empty($produto['id'])): ?>
                    <form method="post" action="<?= site_url('carrinho/adicionar/' . $produto['id']) ?>">
                        <div class="mb-3">
                            <label for="variacao_id" class="form-label">Variação</label>
                            <select class="form-control" id="variacao_id" name="variacao_id" required>
                                <?php foreach ($variacoes as $variacao): ?>
                                    <option value="<?= $variacao['id'] ?>"><?= $variacao['variacao'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" value="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Comprar</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>