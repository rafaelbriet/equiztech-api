<?php

require('../autenticacao/functions.php');

only_logged_users();

require_once('../../dbconnection.php');
require 'MatchRepository.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $request_body = file_get_contents('php://input');
        $match_details = json_decode($request_body, true);
        $started_at = new DateTimeImmutable($match_details['partida']['iniciada_em'], new DateTimeZone($match_details['partida']['fuso_horario']));
        $started_at = $started_at->setTimezone(new DateTimeZone('UTC'));
        $ended_at = new DateTimeImmutable($match_details['partida']['encerrada_em'], new DateTimeZone($match_details['partida']['fuso_horario']));
        $ended_at = $ended_at->setTimezone(new DateTimeZone('UTC'));
        $match_details['partida']['iniciada_em'] = $started_at;
        $match_details['partida']['encerrada_em'] = $ended_at;

        try {
            $connection = create_connection();
            $repository = new MatchRepository($connection);
            $repository->saveMatchResults($match_details);
            $response = [
                'success' => [ 'mensagem' => 'Partida registrada com sucesso' ]
            ];
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