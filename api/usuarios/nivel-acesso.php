<?php

require('../autenticacao/functions.php');

only_admin_allowed();

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $response = get_access_level();
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function get_access_level() {
    try {
        $connection = create_connection();

        $query = $connection->prepare('SELECT id, nome FROM nivel_acesso;');
        $query->execute();
        $result = $query->get_result();
        $response = [
            "nivel_acesso" => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

header('Content-Type: application/json');
echo json_encode($response);