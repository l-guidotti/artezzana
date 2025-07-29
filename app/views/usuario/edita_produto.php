<?php
require_once __DIR__ . '/../../../app/helpers/auth.php';
require_once __DIR__ . '/../../../app/models/Produto.php';
require_once __DIR__ . '/../../../config/database.php';

$pdo = conectar();

verificarLogin();
protegerRotaProdutor();

$produtorId = $_SESSION['usuario_id'];
$produtoModel = new Produto($pdo);
$produtoId = $_GET['id'] ?? null;

if (!$produtoId) {
    $_SESSION['erro_produto'] = 'ID do produto não informado.';
    header('Location: produto.php');
    exit;
}

$produto = $produtoModel->buscarPorId($produtoId);

if (!$produto || $produto['produtor_id'] != $produtorId) {
    $_SESSION['erro_produto'] = 'Produto não encontrado ou acesso não autorizado.';
    header('Location: produto.php');
    exit;
}


$dadosUsuario = pegarDadosUsuario();
$usuario_nome = $dadosUsuario['nome'];
$tipo_usuario_logado = $dadosUsuario['tipo'];
$iniciais_usuario = $dadosUsuario['iniciais'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $unidadeMedida = $_POST['unidadeMedida'] ?? '';
    $quantidadeEstoque = $_POST['quantidadeEstoque'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $ativo = isset($_POST['ativo']) ? true : false;
    $produtoId = $_GET['id'] ?? null;

    // Verifica se tem produtoId
    if (!$produtoId) {
        $_SESSION['erro_produto'] = 'ID do produto não informado.';
        header('Location: produto.php');
        exit;
    }

    // Atualizar no banco (usando seu model)
    $produtoModel = new Produto($pdo);
    $produtoModel->editarProdutoCompleto($produtoId, $nome, $descricao, $preco, $unidadeMedida, $quantidadeEstoque, $categoria, $ativo);

    $_SESSION['msg'] = 'Produto atualizado com sucesso!';
    header('Location: produto.php');  // Redireciona para a lista ou outra página
    exit;
}


$mensagemErro = $_SESSION['erro_produto'] ?? '';
$mensagemSucesso = $_SESSION['msg'] ?? '';

unset($_SESSION['erro_produto'], $_SESSION['msg']);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto: <?= htmlspecialchars($produto['nome'] ?? 'Novo') ?> - Artezzana</title>
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link rel="stylesheet" href="../../../public/css/dashboard-styles.css">
    <link rel="stylesheet" href="../../../public/css/produto-styles.css">
</head>
<body>
    <?php include 'header_produtor.php'; ?>

    <main class="main-content">
        <div class="form-cadastro-produto">
            <div class="form-header">
                <h2>Editar Produto: <?= htmlspecialchars($produto['nome'] ?? '') ?></h2>
                <p>Atualize os detalhes do seu produto.</p>
            </div>

            <?php if ($mensagemErro): ?>
                <div class="message error-message">
                    <p><?= $mensagemErro ?></p>
                </div>
            <?php endif; ?>

            <?php if ($mensagemSucesso): ?>
                <div class="message success-message-php">
                    <p><?= $mensagemSucesso ?></p>
                </div>
            <?php endif; ?>

            <form action="edita_produto.php?id=<?= $produtoId ?>" method="POST">
                <div class="form-group">
                    <label for="nome">Nome do Produto</label>
                    <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($produto['nome'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4" placeholder="Detalhes, ingredientes, forma de uso, etc."><?= htmlspecialchars($produto['descricao'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="preco">Preço</label>
                        <input type="text" id="preco" name="preco" required placeholder="Ex: 15.50" value="<?= htmlspecialchars($produto['preco'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="unidadeMedida">Unidade de Medida</label>
                        <select id="unidadeMedida" name="unidadeMedida" required>
                            <option value="">Selecione</option>
                            <?php
                            $unidades = ['kg', 'un', 'litro', 'pacote', 'duzia', 'pote', 'caixa', 'metro', 'grama', 'ml'];
                            foreach ($unidades as $un) {
                                $selected = (($produto['unidade_medida'] ?? '') === $un) ? 'selected' : '';
                                echo "<option value=\"$un\" $selected>" . htmlspecialchars(ucfirst($un)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantidadeEstoque">Quantidade em Estoque</label>
                        <input type="number" id="quantidadeEstoque" name="quantidadeEstoque" required min="0" value="<?= htmlspecialchars($produto['quantidade_estoque'] ?? 0) ?>">
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoria do Produto</label>
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione</option>
                            <?php
                            $tiposNegocio = [
                                "agricultura" => "Frutas & Verduras",
                                "artesanato" => "Artesanato",
                                "doces" => "Doces & Conservas",
                                "laticinios" => "Laticínios",
                                "plantas" => "Plantas & Mudas",
                                "temperos" => "Temperos & Ervas",
                                "paes" => "Pães",
                                "outros" => "Outros"
                            ];
                            foreach ($tiposNegocio as $value => $label) {
                                $selected = (($produto['categoria'] ?? '') === $value) ? 'selected' : '';
                                echo "<option value=\"$value\" $selected>" . htmlspecialchars($label) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group checkbox-container-custom">
                    <input type="checkbox" id="ativo" name="ativo" <?= (($produto['ativo'] ?? false) ? 'checked' : '') ?>>
                    <label for="ativo">Produto Ativo (Visível para venda)</label>
                </div>

                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        </div>
    </main>

    <?php include 'footer_produtor.php'; ?>

    <script src="../js/dashboard-script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formatação de preço (similar ao cadastrar_produto.php)
            const precoInput = document.getElementById('preco');
            if (precoInput) {
                precoInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^0-9,.]/g, '');
                    value = value.replace(/,/g, '.');
                    const parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts[0] + '.' + parts.slice(1).join('');
                    }
                    e.target.value = value;
                });
            }
        });
    </script>
</body>
</html>