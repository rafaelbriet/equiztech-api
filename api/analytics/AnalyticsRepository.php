<?php

class AnalyticsRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    function getTotalUsers() {
        try {
            $query = 'SELECT COUNT(*) AS total_usuarios FROM usuarios';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalAdmin() {
        try {
            $query = 'SELECT COUNT(*) AS total_administradores FROM usuarios WHERE id_nivel_acesso = 1';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalPlayers() {
        try {
            $query = 'SELECT COUNT(*) AS total_jogadores FROM usuarios WHERE id_nivel_acesso = 2';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalCategories() {
        try {
            $query = 'SELECT COUNT(*) AS total_categorias FROM categorias';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalQuizzes() {
        try {
            $query = 'SELECT COUNT(*) AS total_perguntas FROM perguntas';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    function getTotalQuizzesByCategory() {
        try {
            $query = 'SELECT categorias.nome AS nome_categoria, COUNT(*) AS total_perguntas FROM perguntas
                      INNER JOIN categorias ON categorias.id = perguntas.id_categoria
                      GROUP BY categorias.id
                      ORDER BY total_perguntas DESC';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalMatches() {
        try {
            $query = 'SELECT COUNT(*) AS total_partidas FROM partidas';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalQuizzesAnswered() {
        try {
            $query = 'SELECT COUNT(*) AS total_perguntas_respondidas FROM respostas_partida';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalQuizzesAnsweredCorrectly() {
        try {
            $query = 'SELECT COUNT(*) AS total_perguntas_respondidas_corretamente FROM respostas_partida
                      INNER JOIN respostas ON respostas.id = id_resposta_escolhida
                      WHERE respostas.correta = 1
                      GROUP BY respostas.correta';
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}