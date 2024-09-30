<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dashboard - Equiztech</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script type="module" src="./node_modules/js-cookie/dist/js.cookie.min.js"></script>
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <form method="post" id="form-login">
            <img class="mb-4 mx-auto d-block" src="./dashboard/images/logo_equiztech.svg" alt="" height="50">

            <div class="form-floating">
                <input type="email" class="form-control email" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control senha" id="floatingPassword" placeholder="Senha">
                <label for="floatingPassword">Senha</label>
            </div>

            <div class="form-floating"></div>
            
            <p class="p-2 border border-danger-subtle rounded-2 bg-danger-subtle text-danger d-none" id="form-invalid">E-mail e/ou senha incorretos. Por favor, tente novamente.</p>

            <button class="btn btn-primary w-100 py-2 btn-login" type="submit">Entrar</button>

        </form>
        <p class="mt-2 text-center">
            <a href="#">Esqueci minha senha</a>
        </p>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="login.js"></script>
</body>

</html>