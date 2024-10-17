<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('../../vendor/autoload.php');
require('../autenticacao/functions.php');
require_once('../../dbconnection.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $response = get_user_by_email($data['usuario']['email']);
        break;
    case 'GET':
        $token_id = $_GET['id'] ?? '';
        $token = $_GET['token'] ?? '';
        $response = verify_token($token_id, $token);
        break;
    default:
        $response = [
            'erro' => [ 'mensagem' => 'Método HTTP não suportado.' ]
        ];
        break;
}

function verify_token($token_id, $token) {
    try {
        // Verifica se o token existe
        $connection = create_connection();
        $query = $connection->prepare('SELECT id, email, token, data_validade FROM redefinicao_senha WHERE id = ?');
        $query->bind_param('i', $token_id);
        $query->execute();
        $result = $query->get_result();
        $data = $result->fetch_assoc();

        if ($result->num_rows == 0) {
            return $response = [ 'erro' => [ 'mensagem' => 'Token não encontrado.' ] ];
        }

        // Verifica se o token é válido
        $token_decoded = urldecode($token);

        if (password_verify($token_decoded, $data['token']) == false) {
            return $response = [ 'erro' => [ 'mensagem' => 'Token inválido.' ] ];
        }

        // Verifica se o token não passou da data de válidade
        $now = new DateTimeImmutable('NOW', new DateTimeZone('UTC'));
        $expire_date = DateTime::createFromFormat('Y-m-d H:i:s', $data['data_validade'], new DateTimeZone('UTC'));

        if ($now > $expire_date) {
            return $response = [ 'erro' => [ 'mensagem' => 'Token expirado.' ], 'now' => $now->format('Y-m-d H:i:s'), 'expire_date' => $expire_date->format('Y-m-d H:i:s') ];
        }

        $response = [ 'sucesso' => [ 'mensagem' => 'Token aceito.' ] ];
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function get_user_by_email($email) {
    try {
        // Verifica se o email é de um usuário cadastrado
        $connection = create_connection();
        $query = $connection->prepare('SELECT email FROM usuarios WHERE email = ?');
        $query->bind_param('s', $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 0) {
            return $response = [ 'erro' => ['mensagem' => 'E-mail não econtrado.'] ];
        }

        // Gera um novo token de redefinição de senha
        $random_password = random_bytes(64);
        $random_password_encoded = base64_encode($random_password);
        $created_date = new DateTimeImmutable('NOW', new DateTimeZone('UTC'));
        $expire_date = $created_date->add(DateInterval::createFromDateString($_ENV['PASSWORD_RESET_TOKEN_DURATION_IN_MINUTES']));
        $created_date_string = $created_date->format('Y-m-d H:i:s');
        $expire_date_string = $expire_date->format('Y-m-d H:i:s');

        // Verifica se o usuário já tem um token de redefinição de senha criado
        $query = $connection->prepare('SELECT * FROM redefinicao_senha WHERE email = ?');
        $query->bind_param('s', $email);
        $query->execute();
        $result = $query->get_result();

        // Caso já exista um, deleta ele do banco de dados
        if ($result->num_rows > 0) {
            $saved_token = $result->fetch_assoc();
            $query = $connection->prepare('DELETE FROM redefinicao_senha WHERE id = ?');
            $query->bind_param('i', $saved_token['id']);
            $query->execute();
        }

        // Insere o novo token no banco de dados
        $random_password_hashed = password_hash($random_password_encoded, PASSWORD_BCRYPT);
        $query = $connection->prepare('INSERT INTO redefinicao_senha (email, token, data_criacao, data_validade) VALUES (?, ?, ?, ?)');
        $query->bind_param('ssss', $email, $random_password_hashed, $created_date_string, $expire_date_string);
        $query->execute();
        $token_id = $query->insert_id;
        
        // Gera a URL para redefinir a senha
        // TODO: Enviar email com a URL por email.
        $random_password_url_encoded = urlencode($random_password_encoded);
        $params = [
            'id' => $token_id,
            'token' => $random_password_url_encoded
        ];
        $url = $_ENV['BASE_URL'] . '/dashboard/esqueci-minha-senha/etapa-3.php?' . http_build_query($params);
        $response = send_reset_link($email, $url);
    } catch (\Throwable $th) {
        $response = [
            'erro' => [ 'mensagem' => 'Ocorreu um erro. Estamos trabalhando nisso e consertaremos em breve. Obrigado pela sua paciência!' ]
        ];
    }

    return $response;
}

function send_reset_link($email, $url) {
    $mailer = new PHPMailer(true);

    $messageBody = "
        <h2>Solicitação de Redefinição de Senha</h2>
        <p>Olá,</p>
        <p>Recebemos uma solicitação para redefinir a sua senha. Para continuar com o processo, clique no link abaixo ou copie e cole-o em seu navegador:</p>
        <p><a href=\"{$url}\">{$url}</a></p>
        <p><strong>Lembre-se:</strong> este link é exclusivo para você e não deve ser compartilhado com ninguém. Se você não solicitou a redefinição de senha, sinta-se à vontade para ignorar esta mensagem e excluí-la.</p>
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
        $mailer->Subject = 'Equipe Equiztech - Solicitação de Redefinição de Senha';
        $mailer->Body = $messageBody;
        $mailer->AltBody = strip_tags($messageBody);
        $mailer->send();

        return [ 'sucesso' => [ 'mensagem' => 'Token gerado com sucesso.'] ];
    } catch (Exception $e) {
        return [ 'erro' => [ 'mensagem' => 'Erro ao gerar o token. Tente novamente mais tarde.'] ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);