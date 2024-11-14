<?php

class AchievementRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    function getAll(int $user_id) {
        try {
            $result = [
                $this->getTotalMatchsPlayed($user_id),
                $this->getTotalAnswers($user_id),
                $this->getTotalCorrectAnswers($user_id),
                $this->getTotalMatchesPlayedSingleDay($user_id),
                $this->getLongestStreakDaysPlayed($user_id),
            ];

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
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
            $query = 'SELECT 
                        MAX(total_partida_dia) as maior_quantidade_partida_dia
                    FROM (
                        SELECT 
                            DATE(iniciada_em) as data_partida,
                            COUNT(*) as total_partida_dia
                        FROM partidas
                        WHERE id_usuario = ?
                        GROUP BY data_partida
                    ) AS partidas_por_dia';
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
            $query = 'SELECT 
                          MAX(dias_consecutivos) AS maior_sequencia_consecutiva
                      FROM (
                          SELECT COUNT(*) AS dias_consecutivos
                          FROM (
                              SELECT data_partida,
                                  @grupo := IF(data_partida = DATE_ADD(@prev_data_partida, INTERVAL 1 DAY), @grupo, @grupo + 1) AS grupo,
                                  @prev_data_partida := data_partida
                              FROM (
                                  SELECT DISTINCT DATE(iniciada_em) AS data_partida
                                  FROM partidas
                                  WHERE id_usuario = ?
                                  ORDER BY data_partida
                              ) AS datas
                              CROSS JOIN (SELECT @grupo := 0, @prev_data_partida := NULL) AS vars
                          ) AS grupos
                          GROUP BY grupo
                      ) AS sequencias';
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