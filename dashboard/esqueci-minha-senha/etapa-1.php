<?php

require(dirname(__FILE__) . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etapa 1: Esqueci minha senha - Equiztech</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="<?php echo $_ENV['BASE_URL']; ?>/node_modules/js-cookie/dist/js.cookie.min.js"></script>
    <script src="<?php echo $_ENV['BASE_URL']; ?>/config.js"></script>
    <style>
        html,
        body {
        height: 100%;
        }

        .form-password-reset {
            max-width: 330px;
            padding: 1rem;
        }

        .form-password-reset input {
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-password-reset w-100 m-auto">
        <img class="mb-4 mx-auto d-block" src="../images/logo_equiztech.svg" alt="" height="50">
        <form method="post" id="form-login">
            <div class="form-floating">
                <input type="email" class="form-control email" id="userEmail" placeholder="name@example.com">
                <label for="floatingInput">Email</label>
            </div>
            
            <p class="p-2 border border-danger-subtle rounded-2 bg-danger-subtle text-danger d-none" id="form-invalid">E-mail incorreto. Por favor, tente novamente.</p>

            <button class="btn btn-primary w-100 py-2 btn-login" type="submit">Continuar</button>
        </form>
        <p class="mt-2 text-center">
            <a href="<?php echo $_ENV['BASE_URL']; ?>">Cancelar</a>
        </p>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="etapa-1.js"></script>
</body>

</html>