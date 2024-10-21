<?php

class QuestionRepository {
    private $connection;

    function __construct($connection)
    {
        $this->connection = $connection;    
    }

    function getQuizByCategoryId(int $category_id, int $limit = 5) {
        try {
            $query = $this->connection->prepare('SELECT id, texto_pergunta, explicacao FROM perguntas WHERE id_categoria = ? AND ativo = 1 ORDER BY RAND() LIMIT ?');
            $query->bind_param('ii', $category_id, $limit);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                $response = [ 'perguntas' => [] ];
    
                while ($row = $result->fetch_assoc()) {
                    array_push($response['perguntas'], array_merge($row, $this->getAnswersByQuestionId($row['id'])));
                }
            } else {
                $response = [
                    'erro' => [ 'mensagem' => 'Não foi encontrado uma pergunta com o ID fornecido.' ]
                ];
            }
        } catch (\Throwable $th) {
            $response = [
                'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
            ];
        }
    
        return $response;
    }

    function getAnswersByQuestionId($question_id) {
        try {
            $query = $this->connection->prepare('SELECT id, texto_alternativa, correta FROM respostas WHERE id_pergunta = ?');
            $query->bind_param('i', $question_id);
            $query->execute();
            $result = $query->get_result();
            
            if ($result->num_rows > 0) {
                $response = [
                    "respostas" => $result->fetch_all(MYSQLI_ASSOC)
                ];
            } else {
                $response = [
                    'erro' => [ 'mensagem' => 'Não foi encontrado uma pergunta com o ID fornecido.' ]
                ];
            }
        } catch (\Throwable $th) {
            $response = [
                'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
            ];
        }
    
        return $response;
    }
}