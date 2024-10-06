<?php

require('../autenticacao/functions.php');

only_logged_users();

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $response = get_current_logged_user();
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function get_current_logged_user() {
    try {
        $token = verify_login();
        $response = $token;
        // $connection = create_connection();
        // $query = $connection->prepare('SELECT usuarios.id as id, email, termos_condicoes, id_nivel_acesso, nivel_acesso.nome as nome_nivel_acesso, id_dados_pessoais, dados_pessoais.nome as nome, sobrenome, data_nascimento, biografia, nome_foto, criado_em FROM usuarios INNER JOIN dados_pessoais ON dados_pessoais.id = usuarios.id INNER JOIN nivel_acesso ON nivel_acesso.id = id_nivel_acesso WHERE usuarios.id = ?;');
        // $query->bind_param('i', $id);
        // $query->execute();
        // $result = $query->get_result();
        
        // if ($result->num_rows > 0) {
        //     $response = [
        //         "usuario" => $result->fetch_assoc()
        //     ];
        // } else {
        //     $response = [
        //         'erro' => [ 'mensagem' => 'Não foi possivel encontrar um usuário com o ID fornecido.' ]
        //     ];
        // }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

header('Content-Type: application/json');
echo json_encode($response);