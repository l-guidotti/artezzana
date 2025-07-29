<?php
require_once __DIR__ . '/../../../app/helpers/auth.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../app/controllers/ProdutoController.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = conectar();
    $controller = new ProdutoController($pdo);
    $controller->cadastrar();
}

verificarLogin();
protegerRotaProdutor();

$dadosUsuario = pegarDadosUsuario();
$usuario_nome = $dadosUsuario['nome'];
$tipo_usuario_logado = $dadosUsuario['tipo'];
$iniciais_usuario = $dadosUsuario['iniciais'];

$mensagemErro = $_SESSION['erro_produto'] ?? null;
$mensagemSucesso = $_SESSION['sucesso_produto'] ?? null;
unset($_SESSION['erro_produto'], $_SESSION['sucesso_produto']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto - Artezzana</title>
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link rel="stylesheet" href="../../../public/css/dashboard-styles.css">
    <link rel="stylesheet" href="../../../public/css/cadastra-prod-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">  
</head>
<body>
    <?php include 'header_produtor.php'; ?>

    <main class="main-content">
        <div class="form-cadastro-produto">
            <div class="form-header">
                <h2>Cadastrar Novo Produto</h2>
                <p>Preencha os detalhes do seu produto para colocá-lo à venda.</p>
            </div>

            <form action="cadastra_produto.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome do Produto</label>
                    <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($nome ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4" placeholder="Detalhes, ingredientes, forma de uso, etc."><?= htmlspecialchars($descricao ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="preco">Preço</label>
                        <input type="text" id="preco" name="preco" required placeholder="Ex: 15.50" value="<?= htmlspecialchars($preco ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="unidadeMedida">Unidade de Medida</label>
                        <select id="unidadeMedida" name="unidadeMedida" required>
                            <option value="">Selecione</option>
                            <option value="kg" <?= (($unidadeMedida ?? '') === 'kg' ? 'selected' : '') ?>>Kilograma (kg)</option>
                            <option value="un" <?= (($unidadeMedida ?? '') === 'un' ? 'selected' : '') ?>>Unidade (un)</option>
                            <option value="litro" <?= (($unidadeMedida ?? '') === 'litro' ? 'selected' : '') ?>>Litro (L)</option>
                            <option value="pacote" <?= (($unidadeMedida ?? '') === 'pacote' ? 'selected' : '') ?>>Pacote</option>
                            <option value="duzia" <?= (($unidadeMedida ?? '') === 'duzia' ? 'selected' : '') ?>>Dúzia</option>
                            <option value="pote" <?= (($unidadeMedida ?? '') === 'pote' ? 'selected' : '') ?>>Pote</option>
                            <option value="caixa" <?= (($unidadeMedida ?? '') === 'caixa' ? 'selected' : '') ?>>Caixa</option>
                            <option value="metro" <?= (($unidadeMedida ?? '') === 'metro' ? 'selected' : '') ?>>Metro (m)</option>
                            <option value="grama" <?= (($unidadeMedida ?? '') === 'grama' ? 'selected' : '') ?>>Grama (g)</option>
                            <option value="ml" <?= (($unidadeMedida ?? '') === 'ml' ? 'selected' : '') ?>>Mililitro (ml)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="estoque">Quantidade em Estoque</label>
                        <input type="number" id="estoque" name="estoque" required min="0" value="<?= htmlspecialchars($estoque ?? 0) ?>">
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
                                $selected = (($categoria ?? '') === $value) ? 'selected' : '';
                                echo "<option value=\"$value\" $selected>" . htmlspecialchars($label) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="imagem">Imagem do Produto</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*"> </div>
                
                <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
            </form>
        </div>
    </main>

    <?php include 'footer_produtor.php'; ?>

    <script src="../js/dashboard-script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formatação de preço
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