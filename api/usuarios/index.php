<?php

require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $response = create_user();
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = get_user_by_id($id);
        } else {
            $response = get_users();
        }
        break; 
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function create_user() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    $user_name = $data['dados_pessoais']['nome'];
    $user_surname = $data['dados_pessoais']['sobrenome'];
    $user_birthday = $data['dados_pessoais']['data_nascimento'];
    $user_birthday_date = date("Y-m-d", strtotime($user_birthday));
    $user_bio = $data['dados_pessoais']['biografia'];
    $user_photo = $data['dados_pessoais']['nome_foto'];
    $user_email = $data['usuario']['email'];
    $user_password = $data['usuario']['senha'];
    $user_password_hashed = password_hash($user_password, PASSWORD_BCRYPT);
    $user_conditions = $data['usuario']['termos_condicoes'];
    $user_access_level_id = $data['usuario']['id_nivel_acesso']; 
    $response = [];

    try {
        $connection = create_connection();
        $user_already_exist = get_user_by_email($user_email);
        
        if ($user_already_exist) {
            $response = [
                'erro' => [ 'mensagem' => 'Já existe um usuário cadastrada com esse e-mail.' ]
            ];
        } else {
            $query = $connection->prepare('INSERT INTO dados_pessoais (nome, sobrenome, data_nascimento, biografia, nome_foto) VALUES (?, ?, ?, ?, ?)');
            $query->bind_param('sssss', $user_name, $user_surname, $user_birthday_date, $user_bio, $user_photo);
            $query->execute();
            $user_personal_data_id = $query->insert_id;

            $query = $connection->prepare('INSERT INTO usuarios (email, senha, termos_condicoes, id_dados_pessoais, id_nivel_acesso) VALUES (?, ?, ?, ?, ?)');
            $query->bind_param('sssii', $user_email, $user_password_hashed, $user_conditions, $user_personal_data_id, $user_access_level_id);
            $query->execute();
            $user_id = $query->insert_id;

            $query = $connection->prepare('SELECT nome FROM nivel_acesso WHERE id = ?');
            $query->bind_param('i', $user_access_level_id);
            $query->execute();
            $result = $query->get_result();

            $response = [
                'usuario' => [
                    'id' => $user_id,
                    'email' => $user_email,
                    'termos_condicoes' => $user_conditions,
                    'id_nivel_acesso' => $user_access_level_id,
                    'nome_nivel_acesso' => $result->fetch_assoc()['nome'],
                    'id_dados_pessoais' => $user_personal_data_id,
                    'dados_pessoais' => [
                        'nome' => $user_name,
                        'sobrenome' => $user_surname,
                        'data_nascimento' => $user_birthday_date,
                        'biografia' => $user_bio,
                        'nome_foto' => $user_photo,
                    ]
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

function get_users() {
    try {
        $connection = create_connection();

        $query = $connection->prepare('SELECT usuarios.id as id, email, termos_condicoes, id_nivel_acesso, nivel_acesso.nome as nome_nivel_acesso, id_dados_pessoais, dados_pessoais.nome as nome, sobrenome, data_nascimento, biografia, nome_foto, criado_em FROM usuarios INNER JOIN dados_pessoais ON dados_pessoais.id = usuarios.id INNER JOIN nivel_acesso ON nivel_acesso.id = id_nivel_acesso;');
        $query->execute();
        $result = $query->get_result();
        $response = [
            "usuarios" => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function get_user_by_id($id) {
    try {
        $connection = create_connection();
        $query = $connection->prepare('SELECT usuarios.id as id, email, termos_condicoes, id_nivel_acesso, nivel_acesso.nome as nome_nivel_acesso, id_dados_pessoais, dados_pessoais.nome as nome, sobrenome, data_nascimento, biografia, nome_foto, criado_em FROM usuarios INNER JOIN dados_pessoais ON dados_pessoais.id = usuarios.id INNER JOIN nivel_acesso ON nivel_acesso.id = id_nivel_acesso WHERE usuarios.id = ?;');
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            $response = [
                "usuário" => $result->fetch_assoc()
            ];
        } else {
            $response = [
                'erro' => [ 'mensagem' => 'Não foi possivel encontrar um usuário com o ID fornecido.' ]
            ];
        }
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function get_user_by_email($email) {
    try {
        $connection = create_connection();
        $query = $connection->prepare('SELECT id, email, criado_em, id_dados_pessoais, id_nivel_acesso FROM usuarios WHERE email = ?');
        $query->bind_param('s', $email);
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