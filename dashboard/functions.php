<?php

require('../vendor/autoload.php');

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function only_admin_allowed() {
    if (!isset($_COOKIE['TOKEN'])) {
        echo 'Você precisa estar logado para acessar esta página. <a href="#">Voltar para o login.</a>';
        exit;
    }
    
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    
    $authorization = $_COOKIE['TOKEN'];
    $token = trim(str_replace('Bearer', '', $authorization));
    
    try {
        $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
    } catch (\Throwable $th) {
        $decoded = [];
    }
    
    if ($decoded->usuario->nome_nivel_acesso !== 'Administrador') {
        echo 'Você não tem permissão para acessar esta página. <a href="#">Voltar para o login.</a>';
        exit;
    }
}

function get_partial($name) {
    include(dirname(__FILE__) . '/partials/' . $name . '.php');
}

function get_header() {
    get_partial('header');
}

function get_footer() {
    get_partial('footer');
}