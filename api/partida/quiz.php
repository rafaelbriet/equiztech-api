<?php

require('../autenticacao/functions.php');

only_logged_users();

require_once('../../dbconnection.php');
require '../perguntas/QuestionRepository.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (!isset($_GET['id_categoria'])) {
            $response = [
                'erro' => [ 'mensagem' => 'É necessário fornecer o ID de uma categoria para que um quiz seja gerado.' ]
            ];
            break;
        }

        $category_id = $_GET['id_categoria'];
        $connection = create_connection();
        $repository = new QuestionRepository($connection);
        $response = $repository->getQuizByCategoryId($category_id);
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

header('Content-Type: application/json');
echo json_encode($response);