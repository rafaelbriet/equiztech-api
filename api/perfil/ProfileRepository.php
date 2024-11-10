<?php

class ProfileRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    function getById(int $user_id) {
        try {
            $query = 'SELECT usuarios.id as id, email, termos_condicoes, id_nivel_acesso, nivel_acesso.nome as nome_nivel_acesso, id_dados_pessoais, dados_pessoais.nome as nome, sobrenome, data_nascimento, biografia, nome_foto, criado_em 
            FROM usuarios 
            INNER JOIN dados_pessoais ON dados_pessoais.id = usuarios.id 
            INNER JOIN nivel_acesso ON nivel_acesso.id = id_nivel_acesso
            WHERE usuarios.id = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getByEmail(string $user_email) {
        try {
            $query = 'SELECT usuarios.id as id, email, termos_condicoes, id_nivel_acesso, nivel_acesso.nome as nome_nivel_acesso, id_dados_pessoais, dados_pessoais.nome as nome, sobrenome, data_nascimento, biografia, nome_foto, criado_em 
            FROM usuarios 
            INNER JOIN dados_pessoais ON dados_pessoais.id = usuarios.id 
            INNER JOIN nivel_acesso ON nivel_acesso.id = id_nivel_acesso
            WHERE email = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $user_email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function update(int $user_id, array $data) {
        try {
            $this->connection->begin_transaction();
            $this->updateEmail($user_id, $data['usuario']['email']);
            $this->updatePersonalData($user_id, $data['dados_pessoais']);
            $this->connection->commit();
            return $this->getById($user_id);
        } catch (\Throwable $th) {
            $this->connection->rollback();
            throw $th;
        }
    }

    function updateEmail(int $user_id, string $email) {
        try {
            $query = 'UPDATE usuarios SET email = ? WHERE id = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('si', $email, $user_id);
            $stmt->execute();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function updatePersonalData(int $user_id, array $personal_data) {
        try {
            $query = 'UPDATE dados_pessoais SET nome = ?, sobrenome = ?, data_nascimento = ?, biografia = ? WHERE id = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('ssssi', $personal_data['nome'], $personal_data['sobrenome'], $personal_data['data_nascimento'], $personal_data['biografia'], $user_id);
            $stmt->execute();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}