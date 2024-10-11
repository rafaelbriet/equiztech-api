<?php

require('../autenticacao/functions.php');

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // $token_id = $_GET['id'] ?? '';
        // $password = $_GET['password'] ?? '';
        $response = reset_password();
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function reset_password() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    try {
        $connection = create_connection();
        // Busca o email do usuário no banco de dados
        $query = $connection->prepare('SELECT email FROM redefinicao_senha WHERE id = ?');
        $query->bind_param('i', $data['id']);
        $query->execute();
        $result = $query->get_result();
        $user_email = $result->fetch_assoc()['email'];

        // Cria o hash da senha para ser salvo no banco de dados
        $user_password_hashed = password_hash($data['senha'], PASSWORD_BCRYPT);

        // Atualiza a senha no banco de dados
        $query = $connection->prepare('UPDATE usuarios SET senha = ? WHERE email = ?');
        $query->bind_param('ss', $user_password_hashed, $user_email);
        $query->execute();

        $response = [
            'sucesso' => [ 'mensagem' => 'A senha foi redefinida com sucesso.' ]
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