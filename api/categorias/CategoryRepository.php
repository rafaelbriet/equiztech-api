<?php

class CategoryRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;    
    }

    function create(string $name): array {
        try {
            $query = 'INSERT INTO categorias (nome) VALUES (?)';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            return [
                'id' => $this->connection->insert_id,  
                'nome' => $name,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getAll() {
        try {
            $query = 'SELECT id, nome FROM categorias';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    function getById(int $id): array|null {
        try {
            $query = 'SELECT id, nome FROM categorias WHERE id = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getByName(string $name): array|null {
        try {
            $query = 'SELECT id, nome FROM categorias WHERE nome = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function edit(int $id, string $name): array|null {
        try {
            $query = 'UPDATE categorias SET nome = ? WHERE id = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('si', $name, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return $this->getById($id);
            }

            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function delete(int $id): bool {
        try {
            $query = 'DELETE FROM categorias WHERE id = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}