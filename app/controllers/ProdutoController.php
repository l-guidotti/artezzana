<?php
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../../config/database.php';

class ProdutoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->produtoModel = new Produto($pdo);
        session_start();
    }

    public function cadastrar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nome = $_POST["nome"] ?? '';
            $descricao = $_POST["descricao"] ?? '';
            $preco = $_POST["preco"] ?? '';
            $unidade = $_POST["unidadeMedida"] ?? '';
            $estoque = $_POST["estoque"] ?? 0;
            $categoria = $_POST["categoria"] ?? '';
            if ($_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $nomeImagem = uniqid() . '_' . $_FILES['imagem']['name'];
                $caminhoFisico = __DIR__ . '/../../public/imagens_produtos/' . $nomeImagem;
                $caminhoImagem = '/artezzana/public/imagens_produtos/' . $nomeImagem;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoFisico);

            } else {
                $caminhoImagem = '../../../public/imagens_produtos/imagem_padrao.jpeg';
            }
            $produtor_id = $_SESSION["usuario_id"] ?? null;

            if (!$produtor_id) {
                $_SESSION['erro_produto'] = "Usuário não autenticado.";
                header("Location: ./login.php");
                exit();
            }

            $permitidos = ['jpg', 'jpeg', 'png', 'gif'];
            $tamanhoMaximo = 2 * 1024 * 1024; // 2MB

            $produtoModel = new Produto($this->pdo);
            $produtoModel->criar([
                'nome' => $nome,
                'descricao' => $descricao,
                'preco' => $preco,
                'unidadeMedida' => $unidade,
                'estoque' => $estoque,
                'categoria' => $categoria,
                'imagem' => $caminhoImagem,
                'produtor_id' => $produtor_id
            ]);

            $_SESSION['msg'] = "Produto cadastrado com sucesso!";
            header("Location: ../usuario/produto.php");
            exit();
        } else {
            $_SESSION['erro_produto'] = "Erro ao cadastrar produto.";
            header("Location: ../usuario/produto.php");
            exit();
        }
    }

    public function editar($id) {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $quantidade = $_POST['quantidade'];

        $this->produtoModel->editarProduto($id, $nome, $descricao, $preco, $quantidade);
        
        header("Location: ../usuario/produto.php");
        exit();
    }

    public function excluir($id) {
        $this->produtoModel->excluirProduto($id);

        header("Location: /artezzana/app/views/usuario/produto.php");
        exit();
    }
}

if (isset($_GET['action'])) {
    require_once __DIR__ . '/../models/Produto.php';
    require_once __DIR__ . '/../../config/database.php';

    $pdo = Database::conectar();
    $produtoModel = new Produto($pdo);
    $controller = new ProdutoController($pdo);

    $action = $_GET['action'];

    switch ($action) {
        case 'excluir':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $controller->excluir($id);
            } else {
                echo "ID do produto não informado!";
            }
            break;
    }
}
