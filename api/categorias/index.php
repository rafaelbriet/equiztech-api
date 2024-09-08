<?php

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $response = create_category();
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = get_category_by_id($id);
        } else {
            $response = get_categories();
        }
        break; 
    case 'PUT':
        $response = update_category();
        break;    
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function update_category() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    $category_name = $data['categoria']['nome'];
    $category_id = $_GET['id'];

    try {
        $connection = create_connection();
        $category_already_exist = get_category_by_name($category_name);

        if ($category_already_exist) {
            $response = [
                'erro' => [ 'mensagem' => 'Já existe uma categoria cadastrada com esse nome.' ]
            ];
        } else {
            $query = $connection->prepare('UPDATE categorias SET nome = ? WHERE id = ?');
            $query->bind_param('si', $category_name, $category_id);
            $query->execute();
            
            if ($query->affected_rows > 0) {
                $response = $response = [
                    "categoria" => [
                        "id" => $category_id,
                        "nome" => $category_name,
                    ]
                ];
            } else {
                $response = [
                    'erro' => [ 'mensagem' => 'Não foi possivel encontrar uma categoria com o ID fornecido.' ]
                ];
            }
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
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

function get_category_by_id($id) {
    $response = [];

    try {
        $connection = create_connection();
        $query = $connection->prepare('SELECT id, nome FROM categorias WHERE id = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            $response = $result->fetch_assoc();
        } else {
            $response = [
                'erro' => [ 'mensagem' => 'Não foi possivel encontrar uma categoria com o ID fornecido.' ]
            ];
        }
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