<?php

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
        return json_encode([ 'erro' => [ 'mensagem' => 'Nenhum dado enviado.' ] ]);
    }

    try {
        $connection = create_connection();

        // Verifica se já existe um usuário cadastrado com o mesmo email
        $query = $connection->prepare('SELECT id FROM usuarios WHERE email = ?');
        $query->bind_param('s', $data['usuario']['email']);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            return [ 'erro' => [ 'mensagem' => 'Já existe um usuário cadastrada com esse e-mail.' ] ];
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