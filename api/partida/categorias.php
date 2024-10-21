<?php

require('../autenticacao/functions.php');

only_logged_users();

require_once('../../dbconnection.php');
require '../categorias/CategoryRepository.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $connection = create_connection();
        $repository = new CategoryRepository($connection);
        $response = $repository->getAll();
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

header('Content-Type: application/json');
echo json_encode($response);