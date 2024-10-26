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

    function getTotalCorrectAnswers(int $user_id) {
        try {
            $query = 'SELECT COUNT(*) AS total_respostas_correta FROM respostas_partida
                      INNER JOIN partidas on partidas.id = respostas_partida.id_partida
                      INNER JOIN respostas on respostas.id = respostas_partida.id_resposta_escolhida
                      WHERE partidas.id_usuario = ? AND respostas.correta = 1';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getTotalMatchesPlayedSingleDay(int $user_id) {
        try {
            $query = 'WITH
                      partidas(data_partida) AS (
                          select CAST(iniciada_em AS DATE) AS data_partida
                          FROM partidas
                          WHERE id_usuario = ?
                          ORDER BY data_partida
                      ),
                      partidas_por_dia AS (
                          SELECT
                              data_partida, 
                              COUNT(*) AS total_partidas
                          FROM partidas
                          GROUP BY data_partida
                      )
                      SELECT MAX(total_partidas) AS maior_quantidade_partida_dia
                      FROM partidas_por_dia';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function getLongestStreakDaysPlayed(int $user_id) {
        try {
            $query = 'WITH
                      partidas(data_partida) AS (
                          select CAST(iniciada_em AS DATE) AS data_partida
                          FROM partidas
                          WHERE id_usuario = ?
                          ORDER BY data_partida
                      ),
                      partidas_por_dia AS (
                          SELECT
                              data_partida, 
                              COUNT(*) AS total_partidas
                          FROM partidas
                          GROUP BY data_partida
                      )
                      SELECT MAX(total_partidas) AS maior_sequencia_dia_jogados
                      FROM partidas_por_dia';
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