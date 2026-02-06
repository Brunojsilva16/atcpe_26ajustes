<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

class AssociadoModel
{
    private $conn;
    private $table = 'associados_25';

    public function __construct()
    {
        $this->conn = Connection::getInstance();
    }

    /**
     * Busca um associado pelo email (usado no login)
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um associado pelo ID
     */
    public function findById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_associados = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca associados para a lista pública (Pesquisa)
     * Filtra apenas ativos e públicos se necessário
     */
    public function getPublicList(string $search = '')
    {
        $sql = "SELECT id_associados, nome, nomever, crp, cidade_at, uf, foto, user_tipo 
                FROM {$this->table} 
                WHERE id_status = 1"; // Assumindo 1 como ativo

        if (!empty($search)) {
            $sql .= " AND (nome LIKE :search OR nomever LIKE :search OR crp LIKE :search)";
        }

        $sql .= " ORDER BY nome ASC";

        $stmt = $this->conn->prepare($sql);
        
        if (!empty($search)) {
            $term = "%{$search}%";
            $stmt->bindValue(':search', $term);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza o perfil do associado
     */
    public function updateProfile(int $id, array $data)
    {
        // Exemplo simplificado de update com os campos principais
        $sql = "UPDATE {$this->table} SET 
                nomever = :nomever,
                celular = :celular,
                instagram = :instagram,
                cidade_at = :cidade_at,
                endereco = :endereco,
                numero = :numero,
                bairro_at = :bairro_at,
                cep = :cep,
                uf = :uf,
                publico_atend = :publico_atend,
                acomp_terapeutico = :acomp_terapeutico,
                mini_curr = :mini_curr,
                updated_at = NOW()
                WHERE id_associados = :id";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':nomever', $data['nomever']);
        $stmt->bindValue(':celular', $data['celular']);
        $stmt->bindValue(':instagram', $data['instagram']);
        $stmt->bindValue(':cidade_at', $data['cidade_at']);
        $stmt->bindValue(':endereco', $data['endereco']);
        $stmt->bindValue(':numero', $data['numero']);
        $stmt->bindValue(':bairro_at', $data['bairro_at']);
        $stmt->bindValue(':cep', $data['cep']);
        $stmt->bindValue(':uf', $data['uf']);
        $stmt->bindValue(':publico_atend', $data['publico_atend']);
        $stmt->bindValue(':acomp_terapeutico', $data['acomp_terapeutico']);
        $stmt->bindValue(':mini_curr', $data['mini_curr']);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    /**
     * Atualiza a senha
     */
    public function updatePassword(int $id, string $hash)
    {
        $sql = "UPDATE {$this->table} SET senha = :senha, updated_at = NOW() WHERE id_associados = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':senha', $hash);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}