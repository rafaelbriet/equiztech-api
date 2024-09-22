<?php

if (!isset($_COOKIE['TOKEN'])) {
    echo 'Você precisa estar logado para acessar esta página. <a href="#">Voltar para o login.</a>';
    exit;
}

require('../vendor/autoload.php');

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Equiztech</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-body-tertiary">
    <nav class="navbar bg-light-subtle sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="./images/logo_equiztech.svg" alt="Logo Equiztech" height="32">
            </a>
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="sidebar border border-right border-md-0 col-md-3 col-lg-2 p-0" aria-labelledby="sidebarMenuLabel">
                <div class="offcanvas-md offcanvas-end" id="sidebarMenu">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="sidebarMenuLabel">
                            <img src="./images/logo_equiztech.svg" alt="Logo Equiztech" height="32">
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Fechar"></button>
                    </div>

                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3">
                        <ul class="nav flex-column">
                            <li class="navitem"><a href="#" class="nav-link">Meu perfil</a></li>
                            <li class="navitem"><a href="#" class="nav-link">Usuários</a></li>
                            <li class="navitem"><a href="#" class="nav-link">Categorias</a></li>
                            <li class="navitem"><a href="#" class="nav-link">Quizes</a></li>
                        </ul>
                        <hr class="my-1">
                        <ul class="nav flex-column">
                            <li class="navitem"><a href="#" class="nav-link">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>