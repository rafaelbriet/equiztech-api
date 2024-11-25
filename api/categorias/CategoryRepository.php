<?php

class CategoryRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;    
    }

    function getAll() {
        $response = [];
    
        try {
            $this->connection = create_connection();
    
            $query = $this->connection->prepare('SELECT id, nome FROM categorias');
            $query->execute();
            $result = $query->get_result();
            $response = [
                "categorias" => $result->fetch_all(MYSQLI_ASSOC)
            ];
        } catch (\Throwable $th) {
            $response = [
                'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
            ];
        }
    
        return $response;
    }
}