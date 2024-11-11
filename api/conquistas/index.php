<?php

require('../autenticacao/functions.php');

only_logged_users();

require_once('../../dbconnection.php');
require 'AchievementRepository.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (!isset($_GET['id_usuario'])) {
            $response = [
                'erro' => [ 'mensagem' => 'É necessário fornecer o ID de um usuário para exibir suas conquistas.' ]
            ];
            break;
        }

        $user_id = $_GET['id_usuario'];

        only_current_user($user_id);

        $connection = create_connection();
        $repository = new AchievementRepository($connection);
        $result = $repository->getAll($user_id);
        $response = [ 'conquistas' => [] ];

        for ($i = 0; $i < count($result); $i++) { 
            $achievement = $result[$i];

            foreach ($achievement as $key => $value) {
                $response['conquistas'][$key] = $value;
            }
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