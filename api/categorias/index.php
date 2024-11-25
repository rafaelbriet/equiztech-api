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
        $response = update_category($_GET['id'] ?? null);
        break;
    case 'DELETE':
        $response = delete_category($_GET['id'] ?? null);
        break; 
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function delete_category($id) {
    if (validator::intVal()->positive()->validate($id) === false) {
        return [
            'erro' => 'ID inválido',
            'detalhes' => 'ID de uma categoria é um número inteiro positivo.',
        ];
    }

    try {
        $connection = create_connection();
        $repository = new CategoryRepository($connection);
        $was_deleted = $repository->delete($id);

        if ($was_deleted) {
            return [];
        }

        return [
            'erro' => 'ID inexistente',
            'detalhes' => 'Não foi possivel encontrar uma categoria com o ID fornecido.',
        ];
    } catch (\Throwable $th) {

        if (str_contains($th->getMessage(), 'Cannot delete')) {
            return [
                'erro' => 'Ação não permitida',
                'detalhes' => 'Esta categoria não pode ser excluída, pois está vinculada a outros registros.',
            ];
        }
        
        return [
            'erro' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!',
            'detalhes' => $th->getMessage(),
        ];
    }
}

function update_category($id) {
    if (validator::intVal()->positive()->validate($id) === false) {
        return [
            'erro' => 'ID inválido',
            'detalhes' => 'ID de uma categoria é um número inteiro positivo.',
        ];
    }

    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    if ($data === null) {
        return [
            'erro' => 'Erro no JSON',
            'detalhes' => json_last_error_msg(),
        ];
    }

    if (validator::key('nome')->validate($data) === false) {
        return [
            'erro' => 'Erro no JSON',
            'detalhes' => 'Não foi possível encontrar a chave "nome".',
        ];
    }

    if (validator::stringType()->notEmpty()->validate($data['nome']) === false) {
        return [
            'erro' => 'Nome vazio',
            'detalhes' => 'Uma categoria precisa de uma nome.',
        ];
    }

    try {
        $connection = create_connection();
        $repository = new CategoryRepository($connection);
        $category_from_db = $repository->getByName($data['nome']);

        if ($category_from_db !== null) {
            return [
                'erro' => 'Categoria já existe',
                'detalhes' => "Uma categoria com o nome \"{$data['nome']}\" já existe.",
            ];
        }

        $edited_category = $repository->edit($id, $data['nome']);

        if ($edited_category === null) {
            return [
                'erro' => 'ID inexistente',
                'detalhes' => 'Não foi possivel encontrar uma categoria com o ID fornecido.',
            ];
        }

        return $edited_category;
    } catch (\Throwable $th) {
        return [
            'erro' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!',
            'detalhes' => $th->getMessage(),
        ];
    }
}

function create_category() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    if ($data === null) {
        return [
            'erro' => 'Erro no JSON',
            'detalhes' => json_last_error_msg(),
        ];
    }

    if (validator::key('nome')->validate($data) === false) {
        return [
            'erro' => 'Erro no JSON',
            'detalhes' => 'Não foi possível encontrar a chave "nome".',
        ];
    }

    if (validator::stringType()->notEmpty()->validate($data['nome']) === false) {
        return [
            'erro' => 'Nome vazio',
            'detalhes' => 'Uma categoria precisa de uma nome.',
        ];
    }
    
    try {
        $connection = create_connection();
        $repository = new CategoryRepository($connection);
        $category_from_db = $repository->getByName($data['nome']);

        if ($category_from_db !== null) {
            return [
                'erro' => 'Categoria já existe',
                'detalhes' => "Uma categoria com o nome \"{$data['nome']}\" já existe.",
            ];
        }

        $created_category = $repository->create($data['nome']);
        return $created_category;
    } catch (\Throwable $th) {
        return [
            'erro' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!',
            'detalhes' => $th->getMessage(),
        ];
    }
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
                'erro' => 'ID inexistente',
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

header('Content-Type: application/json');
echo json_encode($response);