<?php

// Nada para ver aqui

// TODO: Um usuário precisa conseguir redefinir sua senha
// TODO: um usuário precisa conseguir excluir seu perfil
// TODO: um usuário precisa conseguir atualizar sua foto

require 'header.php';

use Respect\Validation\Validator as validator;

if (!isset($_GET['id_usuario'])) {
    $response = [
        'erro' => [ 'mensagem' => 'É necessário fornecer o ID de um usuário para exibir suas conquistas.' ]
    ];
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode($response);
    exit;
}

$user_id = $_GET['id_usuario'];

only_current_user($user_id);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $connection = create_connection();
        $repository = new ProfileRepository($connection);
        $result = $repository->getById($user_id);
        $response = [ 'usuario' => $result ];
        break;
    case 'PUT':
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        // Validação dos dados enviados pelo usuário
        if (validator::email()->validate($data['usuario']['email']) === false) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([ 'erro' => [ 'mensagem' => 'E-mail inválido. É necessário fornecer um e-mail válido para criar uma conta.' ] ]);
            exit;
        }
        
        if (validator::stringType()->notEmpty()->validate($data['usuario']['dados_pessoais']['nome']) == false) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([ 'erro' => [ 'mensagem' => 'Nome inválido. É necessário fornecer seu nome para criar uma conta' ] ]);
            exit;
        }

        if (validator::stringType()->notEmpty()->validate($data['usuario']['dados_pessoais']['sobrenome']) == false) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([ 'erro' => [ 'mensagem' => 'Sobrenome inválido. É necessário fornecer seu nome para criar uma conta.' ] ]);
            exit;
        }

        if (validator::minAge(13, 'Y-m-d')->validate($data['usuario']['dados_pessoais']['data_nascimento']) == false) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([ 'erro' => [ 'mensagem' => 'Data de nascimento inválido. É necessário ser maior de 13 anos para criar uma conta.' ] ]);
            exit;
        }

        // Cria a conexão com o banco de dados
        $connection = create_connection();
        $repository = new ProfileRepository($connection);

        // Verifica se o email já está sendo utilizado por outro usuário
        $email_in_use = $repository->getByEmail($data['usuario']['email']);

        if ($email_in_use != null && $email_in_use['id'] != $user_id) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([ 'erro' => [ 'mensagem' => 'E-mail inválido. O e-mail já está sendo utilizado por outro usuário.' ], $email_in_use, $user_id ]);
            exit;
        }

        // Atualiza os dados no banco de dados
        $result = $repository->update($user_id, $data);
        $response = $repository->getById($user_id);
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

header('Content-Type: application/json');
echo json_encode($response);