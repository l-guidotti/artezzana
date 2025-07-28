<?php
require_once __DIR__ . '/../../config/database.php';

class Produto {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function criar(array $dados) {
        $sql = "INSERT INTO produtos (nome, descricao, preco, unidade_medida, quantidade_estoque, categoria, imagem_url, produtor_id)
                VALUES (:nome, :descricao, :preco, :unidade, :estoque, :categoria, :imagem, :produtor_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':descricao', $dados['descricao']);
        $stmt->bindValue(':preco', $dados['preco']);
        $stmt->bindValue(':unidade', $dados['unidadeMedida']);
        $stmt->bindValue(':estoque', $dados['estoque']);
        $stmt->bindValue(':categoria', $dados['categoria']);
        $stmt->bindValue(':imagem', $dados['imagem']);
        $stmt->bindValue(':produtor_id', $dados['produtor_id']);
        return $stmt->execute();
    }

   public function listarPorProdutor($produtor_id) {
    $sql = "SELECT * FROM produtos WHERE produtor_id = :id ORDER BY id DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':id', $produtor_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodos() {
        $sql = "SELECT * FROM produtos";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function editarProduto($id, $nome, $descricao, $preco, $quantidade) {
        $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, quantidade = :quantidade WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':preco', $preco);
        $stmt->bindValue(':quantidade', $quantidade);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function editarProdutoCompleto($id, $nome, $descricao, $preco, $unidadeMedida, $quantidadeEstoque, $categoria, $ativo) {
        $sql = "UPDATE produtos SET 
                    nome = :nome, 
                    descricao = :descricao, 
                    preco = :preco, 
                    unidade_medida = :unidade_medida,
                    quantidade_estoque = :quantidade_estoque,
                    categoria = :categoria,
                    ativo = :ativo
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':preco', $preco);
        $stmt->bindValue(':unidade_medida', $unidadeMedida);
        $stmt->bindValue(':quantidade_estoque', $quantidadeEstoque);
        $stmt->bindValue(':categoria', $categoria);
        $stmt->bindValue(':ativo', $ativo, PDO::PARAM_BOOL);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }


    public function excluirProduto($id) {
        $sql = "DELETE FROM produtos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }


}
