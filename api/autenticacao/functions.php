<?php

require('../../vendor/autoload.php');

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

function verify_login() {
    $headers = getallheaders();
    $authorizationHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    if (!isset($authorizationHeader)) {
        error_log('could not find the Authorization header');
        return [];
    }

    $authorization = $authorizationHeader;
    $token = trim(str_replace('Bearer', '', $authorization));

    try {
        $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
    } catch (\Throwable $th) {
        $decoded = [];
    }
    return $decoded; 
}

function is_user_admin() {
    $user = verify_login();

    if (is_user_logged() && $user->usuario->nome_nivel_acesso === 'Administrador') {
        return true;
    }

    return false;
}

function is_user_logged() {
    $user = verify_login();
    return !empty($user);
}

function only_admin_allowed() {
    if (!is_user_admin()) {
        $response = [
            'erro' => [ 'mensagem' => 'Você não tem permissão para acessar esta página.' ]
        ];
        header('Content-Type: application/json');
        header('HTTP/1.1 403 Forbidden');
        echo json_encode($response);
        exit;
    }
}

function only_logged_users() {
    if (!is_user_logged()) {
        $response = [
            'erro' => [ 'mensagem' => 'Você não tem permissão para acessar esta página.' ]
        ];
        header('Content-Type: application/json');
        header('HTTP/1.1 403 Forbidden');
        echo json_encode($response);
        exit;
    }
}

function only_current_user(int $user_id) {
    $current_user = verify_login();

    if ($current_user->usuario->id !== $user_id) {
        $response = [
            'erro' => [ 'mensagem' => 'Você não tem permissão para acessar esta página.' ]
        ];
        header('Content-Type: application/json');
        header('HTTP/1.1 403 Forbidden');
        echo json_encode($response);
        exit;
    }
}