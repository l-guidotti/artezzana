<?php
require_once __DIR__ . '/../../config/database.php'; 
require_once __DIR__ . '/../controllers/LoginController.php'; 

class Usuario {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function criar(array $dados) {
        $sql = "INSERT INTO usuarios (
            nome, sobrenome, email, senha, telefone, cidade, estado, tipo_usuario, termos_aceitos, receber_marketing,
            nome_negocio, tipo_negocio, descricao_negocio, raio_entrega_km
        ) VALUES (
            :nome, :sobrenome, :email, :senha_hash, :telefone, :cidade, :estado, :tipo_usuario, :termos_aceitos, :receber_marketing,
            :nome_negocio, :tipo_negocio, :descricao_negocio, :raio_entrega_km
        )";

        try {
            $stmt = $this->pdo->prepare($sql);

            // Bind dos parâmetros
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':sobrenome', $dados['sobrenome']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':senha_hash', password_hash($dados['senha'], PASSWORD_DEFAULT));
            $stmt->bindValue(':telefone', $dados['telefone']);
            $stmt->bindValue(':cidade', $dados['cidade']);
            $stmt->bindValue(':estado', $dados['estado']);
            $stmt->bindValue(':tipo_usuario', $dados['tipo_usuario']);
            $stmt->bindValue(':termos_aceitos', $dados['termos_aceitos'] ? 't' : 'f', PDO::PARAM_BOOL);
            $stmt->bindValue(':receber_marketing', $dados['receber_marketing'] ? 't' : 'f', PDO::PARAM_BOOL);
            $stmt->bindValue(':nome_negocio', $dados['nome_negocio'] ?? null);
            $stmt->bindValue(':tipo_negocio', $dados['tipo_negocio'] ?? null);
            $stmt->bindValue(':descricao_negocio', $dados['descricao_negocio'] ?? null);
            $stmt->bindValue(':raio_entrega', $dados['raio_entrega'] ?? null, PDO::PARAM_INT);

            $stmt->execute();

            return $this->pdo->lastInsertId(); 
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorEmail(string $email) {
        $sql = "SELECT id, nome, email, senha, tipo_usuario FROM usuarios WHERE email = :email";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId(int $id) {
        $sql = "SELECT id, nome, email, tipo_usuario, nome_negocio, tipo_negocio, descricao_negocio, raio_entrega_km FROM usuarios WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return false;
        }
    }

    public function emailExiste(string $email): bool {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar email existente: " . $e->getMessage());
            return false;
        }
    }

    // Atualiza os dados de um usuário.
    public function atualizar(int $id, array $dados) {
        // Exemplo: UPDATE usuarios SET nome = :nome WHERE id = :id
        return false; 
    }

    // Exclui um usuário.
    public function excluir(int $id) {
        return false; 
    }
}