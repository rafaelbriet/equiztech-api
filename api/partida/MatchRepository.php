<?php

class MatchRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    function saveMatchResults(array $match_details) {
        try {
            $this->connection->begin_transaction();
            $match_id = $this->createMatch($match_details['partida']['id_usuario'], $match_details['partida']['iniciada_em'], $match_details['partida']['encerrada_em'], $match_details['partida']['fuso_horario']);

            foreach ($match_details['partida']['respostas'] as $answer) {
                $this->createMatchAnswer($match_id, $answer['id_pergunta'], $answer['id_resposta_escolhida']);
            }

            $this->connection->commit();
        } catch (\Throwable $th) {
            $this->connection->rollback();
            throw $th;
        }
    }

    function createMatch(int $user_id, DateTimeImmutable $started_at, DateTimeImmutable $ended_at, string $timezone) {
        try {
            $start_datetime = $started_at->format('Y-m-d G:i:s');
            $end_datetime = $ended_at->format('Y-m-d G:i:s');
            $query = 'INSERT INTO partidas (id_usuario, iniciada_em, encerrada_em, fuso_horario) VALUES (?, ?, ?, ?)';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('isss', $user_id, $start_datetime, $end_datetime, $timezone);
            $stmt->execute();
            return $this->connection->insert_id;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function createMatchAnswer(int $match_id, int $question_id, int $answer_id) {
        try {
            $query = 'INSERT INTO respostas_partida (id_partida, id_pergunta, id_resposta_escolhida) VALUES (?, ?, ?)';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('iss', $match_id, $question_id, $answer_id);
            $stmt->execute();
            return $this->connection->insert_id;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}