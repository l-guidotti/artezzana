<?php
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../../config/database.php';

class ProdutoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
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
            $imagem = ''; 
            $produtor_id = $_SESSION["usuario_id"] ?? null;

            if (!$produtor_id) {
                $_SESSION['erro_produto'] = "Usuário não autenticado.";
                header("Location: ./login.php");
                exit();
            }

            // Tratar imagem
            if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);
                $nomeArquivo = uniqid() . "." . $ext;
                $caminho = __DIR__ . '/../../public/imagens_produtos/' . $nomeArquivo;
                move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho);
                $imagem = $nomeArquivo;
            }

            $permitidos = ['jpg', 'jpeg', 'png', 'gif'];
            $tamanhoMaximo = 2 * 1024 * 1024; // 2MB

            if (in_array($ext, $permitidos) && $_FILES["imagem"]["size"] <= $tamanhoMaximo) {
                move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho);
                $imagem = $nomeArquivo;
            } else {
                $_SESSION['erro_produto'] = "Arquivo inválido.";
                header("Location: ../usuario/produto.php");
                exit();
            }

            $produtoModel = new Produto($this->pdo);
            $produtoModel->criar([
                'nome' => $nome,
                'descricao' => $descricao,
                'preco' => $preco,
                'unidadeMedida' => $unidade,
                'estoque' => $estoque,
                'categoria' => $categoria,
                'imagem' => $imagem,
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
}
