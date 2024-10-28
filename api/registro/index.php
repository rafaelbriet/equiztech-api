<?php

use Respect\Validation\Validator as validator;

require('../autenticacao/functions.php');

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $response = register_user();
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

// {
//     "dados_pessoais": {
//         "nome": "Natalia",
//         "sobrenome": "Vitor",
//         "data_nascimento": "1995-09-18"
//     },
//     "usuario": {
//         "email": "natalia@mail.com",
//         "senha": 123,
//         "termos_condicoes": true
//     }
// }

function register_user() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    if (empty($data)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'Nenhum dado enviado.' ] ]);
        exit;
    }

    // Validação dos dados enviados pelo usuário
    if (validator::stringType()->notEmpty()->validate($data['dados_pessoais']['nome']) == false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'Nome inválido. É necessário fornecer seu nome para criar uma conta' ] ]);
        exit;
    }

    if (validator::stringType()->notEmpty()->validate($data['dados_pessoais']['sobrenome']) == false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'Sobrenome inválido. É necessário fornecer seu nome para criar uma conta.' ] ]);
        exit;
    }

    if (validator::minAge(13, 'Y-m-d')->validate($data['dados_pessoais']['data_nascimento']) == false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'Data de nascimento inválido. É necessário ser maior de 13 anos para criar uma conta.' ] ]);
        exit;
    }

    if (validator::email()->validate($data['usuario']['email']) === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'E-mail inválido. É necessário fornecer um e-mail válido para criar uma conta.' ] ]);
        exit;
    }

    if (validator::stringType()->notEmpty()->validate($data['usuario']['senha']) == false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'Senha inválida. É necessário fornecer uma senha para criar uma conta.' ] ]);
        exit;
    }

    if (validator::length(6)->validate($data['usuario']['senha']) === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'Senha inválida. A senha deve conter ao menos 6 caracteres.' ] ]);
        exit;
    }

    if (validator::falseVal()->validate($data['usuario']['termos_condicoes'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([ 'erro' => [ 'mensagem' => 'É necessário aceitar os Termos de Serviço e Política de Privacidade para criar uma conta.' ] ]);
        exit;
    }

    try {
        $connection = create_connection();

        // Verifica se já existe um usuário cadastrado com o mesmo email
        $query = $connection->prepare('SELECT id FROM usuarios WHERE email = ?');
        $query->bind_param('s', $data['usuario']['email']);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            http_response_code(409);
            echo [ 'erro' => [ 'mensagem' => 'Já existe um usuário cadastrada com esse e-mail.' ] ];
            exit;
        }

        // Se o email ainda não estiver sendo usado, registra um novo usuário
        $personal_data = $data['dados_pessoais'];
        $query = $connection->prepare('INSERT INTO dados_pessoais (nome, sobrenome, data_nascimento) VALUES (?, ?, ?)');
        $query->bind_param('sss', $personal_data['nome'], $personal_data['sobrenome'], $personal_data['data_nascimento']);
        $query->execute();
        $user_personal_data_id = $query->insert_id;

        $user_data = $data['usuario'];
        $user_password_hashed = password_hash($user_data['senha'], PASSWORD_BCRYPT);
        $user_access_level = 2;
        $query = $connection->prepare('INSERT INTO usuarios (email, senha, termos_condicoes, id_dados_pessoais, id_nivel_acesso) VALUES (?, ?, ?, ?, ?)');
        $query->bind_param('sssii', $user_data['email'], $user_password_hashed, $user_data['termos_condicoes'], $user_personal_data_id, $user_access_level);
        $query->execute();
        $user_id = $query->insert_id;

        $response = [ 'sucesso' => [ 'mensagem' => 'Usuário registrado com sucesso' ] ];
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

header('Content-Type: application/json');
echo json_encode($response);