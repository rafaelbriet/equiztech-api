<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('../../vendor/autoload.php');
require('../autenticacao/functions.php');

only_admin_allowed();

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
    case 'PUT':
        $response = update_user();
        break;
    case 'DELETE':
        $response = delete_user();
        break; 
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function delete_user() {
    $user_id = $_GET['id'];

    try {
        $connection = create_connection();
        $user = get_user_by_id($user_id);

        if (!isset($user['erro'])) {
            $query = $connection->prepare('DELETE FROM usuarios WHERE id = ?');
            $query->bind_param('i', $user['usuario']['id']);
            $query->execute();

            $query = $connection->prepare('DELETE FROM dados_pessoais WHERE id = ?');
            $query->bind_param('i', $user['usuario']['id_dados_pessoais']);
            $query->execute();

            $response = [];
        } else {
            $response = [
                'erro' => [ 'mensagem' => 'Não foi possivel encontrar uma categoria com o ID fornecido.' ]
            ];
        }

        $query = $connection->prepare('DELETE FROM categorias WHERE id = ?');
        $query->bind_param('i', $category_id);
        $query->execute();
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function update_user() {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    $user_id = $_GET['id'];
    $user_email = $data['usuario']['email'];
    $user_password = $data['usuario']['senha'];
    $user_password_hashed = password_hash($user_password, PASSWORD_BCRYPT);
    $user_access_level_id = $data['usuario']['id_nivel_acesso']; 
    $user_personal_data_id = $data['usuario']['id_dados_pessoais'];
    $user_name = $data['usuario']['nome'];
    $user_surname = $data['usuario']['sobrenome'];
    $user_birthday = $data['usuario']['data_nascimento'];
    $user_birthday_date = date("Y-m-d", strtotime($user_birthday));
    $user_bio = $data['usuario']['biografia'];
    $user_photo = $data['usuario']['nome_foto'];

    try {
        $connection = create_connection();
        $user_already_exist = get_user_by_email($user_email);
        $user_own_email = $user_already_exist ? $user_already_exist['id'] == $user_id : true;

        if ($user_already_exist && !$user_own_email) {
            $response = [
                'erro' => [ 'mensagem' => 'Já existe um usuário cadastrado com esse e-mail.' ],
            ];
        } else {
            $query = $connection->prepare('UPDATE dados_pessoais SET nome = ?, sobrenome = ?, data_nascimento = ?, biografia = ?, nome_foto = ? WHERE id = ?');
            $query->bind_param('sssssi', $user_name, $user_surname, $user_birthday_date, $user_bio, $user_photo, $user_personal_data_id);
            $query->execute();
            $update_successful = $query->affected_rows > 0;
            
            if ($user_password) {
                if ($user_already_exist) {
                    $query = $connection->prepare('UPDATE usuarios SET senha = ?, id_nivel_acesso = ? WHERE id = ?');
                    $query->bind_param('sii', $user_password_hashed, $user_access_level_id, $user_id);
                } else {
                    $query = $connection->prepare('UPDATE usuarios SET email = ?, senha = ?, id_nivel_acesso = ? WHERE id = ?');
                    $query->bind_param('ssii', $user_email, $user_password_hashed, $user_access_level_id, $user_id);
                }
            } else {
                if ($user_already_exist) {
                    $query = $connection->prepare('UPDATE usuarios SET id_nivel_acesso = ? WHERE id = ?');
                    $query->bind_param('ii', $user_access_level_id, $user_id);
                } else {
                    $query = $connection->prepare('UPDATE usuarios SET email = ?, id_nivel_acesso = ? WHERE id = ?');
                    $query->bind_param('sii', $user_email, $user_access_level_id, $user_id);
                }
            }

            $query->execute();

            if ($update_successful) {
                $response = get_user_by_id($user_id);
            } else {
                $response = [
                    'erro' => [ 'mensagem' => 'Não foi possivel encontrar um usuário com o ID fornecido.' ]
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

            send_welcome_email($user_email, $user_name, $user_access_level_id);
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
                "usuario" => $result->fetch_assoc()
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

function send_welcome_email($email, $name, $accessLevel) {
    $mailer = new PHPMailer(true);

    if ($accessLevel == 1) {
        $link = "<li>Baixe o aplicativo Equiztech em sua loja de aplicativos.</li>";
    } else {
        $link = "<li><a href=\"http://{$_ENV['BASE_URL']}\">Acesso o dashboard</a>.</li>";
    }

    $messageBody = "
        <h2>Cadastro realizado com sucesso! 🎉 </h2>
        <p>Olá, {$name}</p>
        <p>Para começar a utilizar a plataforma, siga os passos abaixo:</p>
        <ul>
            {$link}
            <li>Na tela de login, use a opção 'Esqueci minha senha' para redefinir sua senha.</li>
        </ul>
        <p>Se precisar de mais assistência, estamos à disposição!</p>
        <p>Atenciosamente,<br>
        Equipe Equiztech/p>
    ";

    try {
        //Server settings
        $mailer->isSMTP();
        $mailer->Host = $_ENV['SMTP_HOST'];
        $mailer->SMTPAuth = $_ENV['SMTP_AUTH'] == 'true' ? true : false;
        $mailer->Username = $_ENV['SMTP_USERNAME'];
        $mailer->Password = $_ENV['SMTP_PASSWORD'];
        $mailer->Port = (int)$_ENV['SMTP_PORT'];
        
        switch ($_ENV['SMTP_DEBUG']) {
            case 'client':
                $mailer->SMTPDebug = SMTP::DEBUG_CLIENT; 
                break;
            case 'server':
                $mailer->SMTPDebug = SMTP::DEBUG_SERVER; 
                break;
            case 'connection':
                $mailer->SMTPDebug = SMTP::DEBUG_CONNECTION; 
                break;
            case 'lowlevel':
                $mailer->SMTPDebug = SMTP::DEBUG_LOWLEVEL; 
                break;
            default:
                $mailer->SMTPDebug = SMTP::DEBUG_OFF; 
                break;
        }

        switch ($_ENV['SMTP_DEBUG']) {
            case 'value':
                # code...
                break;
            
            default:
                # code...
                break;
        }

        //Recipients
        $mailer->setFrom($mailer->Username, 'Equipe Equiztech');
        $mailer->addAddress($email);

        //Content
        $mailer->isHTML(true);
        $mailer->Subject = 'Equipe Equiztech - Cadastro realizado';
        $mailer->Body = $messageBody;
        $mailer->AltBody = strip_tags($messageBody);
        $mailer->send();

        return [ 'sucesso' => [ 'mensagem' => 'Token gerado com sucesso.'] ];
    } catch (Exception $e) {
        return [ 'sucesso' => [ 'mensagem' => 'Erro ao gerar o token. Tente novamente mais tarde.'] ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);