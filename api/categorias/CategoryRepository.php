<?php

class CategoryRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;    
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
}