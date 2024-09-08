<?php

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $response = create_category();
        break;
    case 'GET':
        $response = get_categories();
        break;     
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function create_category() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    $category_name = $data['categoria']['nome'];
    $response = [];

    try {
        $connection = create_connection();
        $category_already_exist = get_category_by_name($category_name);

        if ($category_already_exist) {
            $response = [
                'erro' => [ 'mensagem' => 'Já existe uma categoria cadastrada com esse nome.' ]
            ];
        } else {
            $query = $connection->prepare('INSERT INTO categorias (nome) VALUES (?)');
            $query->bind_param('s', $category_name);
            $query->execute();
            $response = [
                "categoria" => [
                    "id" => $query->insert_id,
                    "nome" => $category_name,
                ]
            ];
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function get_categories() {
    $response = [];

    try {
        $connection = create_connection();

        $query = $connection->prepare('SELECT id, nome FROM categorias');
        $query->execute();
        $result = $query->get_result();
        $response = [
            "categorias" => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function get_category_by_name($name) {
    $response = [];

    try {
        $connection = create_connection();
        $query = $connection->prepare('SELECT id, nome FROM categorias WHERE nome = ?');
        $query->bind_param('s', $name);
        $query->execute();
        $result = $query->get_result();
        $response = $result->fetch_assoc();
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

header('Content-Type: application/json');
echo json_encode($response);