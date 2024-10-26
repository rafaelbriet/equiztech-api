<?php

class AchievementRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    function getAll() {

    }

    function getTotalMatchsPlayed(int $user_id) {
        try {
            $query = 'SELECT COUNT(*) AS total_partidas FROM partidas WHERE id_usuario = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalAnswers(int $user_id) {
        try {
            $query = 'SELECT COUNT(*) AS total_respostas FROM respostas_partida
                      INNER JOIN partidas on partidas.id = respostas_partida.id_partida
                      WHERE partidas.id_usuario = ?';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}