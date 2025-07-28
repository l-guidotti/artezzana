<?php

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

    // métodos editar(), buscarPorId() e excluir() você pode adicionar depois
}
