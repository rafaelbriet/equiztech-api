<?php

require('../autenticacao/functions.php');

only_admin_allowed();

require_once('../../dbconnection.php');
require 'AnalyticsRepository.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':

        $connection = create_connection();
        $repository = new AnalyticsRepository($connection);
        $response = [
            ...$repository->getTotalUsers(),
            ...$repository->getTotalAdmin(),
            ...$repository->getTotalPlayers(),
            ...$repository->getTotalCategories(),
            ...$repository->getTotalQuizzes(),
            ...$repository->getTotalMatches(),
            "total_pergunta_por_categoria" => $repository->getTotalQuizzesByCategory(),
            ...$repository->getTotalQuizzesAnswered(),
            ...$repository->getTotalQuizzesAnsweredCorrectly(),
        ];
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

header('Content-Type: application/json');
echo json_encode($response);