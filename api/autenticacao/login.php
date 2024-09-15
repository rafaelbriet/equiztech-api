<?php

use Dotenv\Dotenv;
use Firebase\JWT\JWT;

require('../../vendor/autoload.php');
require_once('../../dbconnection.php');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    try {
        $connection = create_connection();

        $query = $connection->prepare('SELECT id, email, senha, id_nivel_acesso FROM usuarios WHERE email = ?');
        $query->bind_param('s', $data['usuario']['email']);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            if (password_verify($data['usuario']['senha'], $usuario['senha'])) {
                $access_level_query = $connection->prepare('SELECT nome FROM nivel_acesso WHERE id = ?');
                $access_level_query->bind_param('i', $usuario['id_nivel_acesso']);
                $access_level_query->execute();
                $access_level_result = $access_level_query->get_result();
                $access_level = $access_level_result->fetch_assoc();
                // Login válido por 7 dias
                $expire_time = 7 * 24 * 60 * 60;
                $payload = [
                    'exp' => time() + $expire_time,
                    'iat' => time(),
                    'usuario' => [
                        'id' => $usuario['id'],
                        'email' => $usuario['email'],
                        'nome_nivel_acesso' => $access_level['nome'],
                    ]
                ];
                $response = [ 'token' => JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256') ];
            } else {
                $response = [
                    "erro" => [ 'mensagem' => 'Usuário e/ou senha inválidos.' ]
                ];
            }
        } else {
            $response = [
                "erro" => [ 'mensagem' => 'Usuário e/ou senha inválidos.' ]
            ];
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => $th->getMessage() ]
            // 'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }
} else {
    $response = [
        'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
    ];
}

header('Content-Type: application/json');
echo json_encode($response);