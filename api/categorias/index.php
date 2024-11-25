<?php

use Respect\Validation\Validator as validator;

require('../autenticacao/functions.php');

only_admin_allowed();

require_once('../../dbconnection.php');
require 'CategoryRepository.php';

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
    case 'DELETE':
        $response = delete_category();
        break; 
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function delete_category() {
    $category_id = $_GET['id'];

    try {
        $connection = create_connection();
        $query = $connection->prepare('DELETE FROM categorias WHERE id = ?');
        $query->bind_param('i', $category_id);
        $query->execute();
        
        if ($query->affected_rows > 0) {
            $response = [];
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
    try {
        $connection = create_connection();
        $repository = new CategoryRepository($connection);
        $categories = $repository->getAll();
        return $categories;
    } catch (\Throwable $th) {
        return [
            'erro' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!',
            'detalhes' => $th->getMessage(),
        ];
    }
}

function get_category_by_id($id) {
    if (validator::intVal()->positive()->validate($id) === false) {
        return [
            'erro' => 'ID inválido',
            'detalhes' => 'ID de uma categoria é um número inteiro positivo.',
        ];
    }

    try {
        $connection = create_connection();
        $repository = new CategoryRepository($connection);
        $category = $repository->getById($id);

        if ($category === null) {
            return [
                'erro' => 'ID inválido',
                'detalhes' => 'Não foi possivel encontrar uma categoria com o ID fornecido.',
            ];
        }
        
        return $category;
    } catch (\Throwable $th) {
        return [
            'erro' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!',
            'detalhes' => $th->getMessage(),
        ];
    }
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