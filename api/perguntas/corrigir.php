<?php

require('../autenticacao/functions.php');

only_logged_users();

require_once('../../dbconnection.php');
require 'QuestionRepository.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);

        try {
            $connection = create_connection();
            $repository = new QuestionRepository($connection);
            $questions = [];
            $total_answer_correct = 0;

            foreach ($data->respostas as $value) {
                $question = $repository->getQuestionAnswerResult($value->id_resposta_escolhida);
                $questions[] = $question;

                if ($question['correta'] == 1) {
                    $total_answer_correct++;
                }
            }

            $response = [ 'total_respostas' => count($questions), 'total_respostas_corretas' => $total_answer_correct, 'respostas' => $questions];
        } catch (\Throwable $th) {
            $response = [
                'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
            ];
        }

        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

header('Content-Type: application/json');
echo json_encode($response);