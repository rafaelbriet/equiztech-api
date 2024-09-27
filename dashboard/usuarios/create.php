<?php

require('../functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

$url = $_ENV['BASE_URL'] . '/api/usuarios/nivel-acesso.php/';
$curl_handler = curl_init($url);
curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
$authorization = 'Authorization: ' . $_COOKIE['TOKEN'];
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
$response_json = curl_exec($curl_handler);
curl_close($curl_handler);
$access_level = json_decode($response_json, true);

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">
            <div class="d-flex justify-content-between flex-wrap flex-md-no-wrap align-items-center border-bottom pt-3 pb-2 mb-3">
                <h1 class="h2">Cadastrar usuário</h1>
            </div>

            <div class="mb-3">
                <form class="form-dashboard">
                    <div class="mb-3">
                        <label for="userName" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="userName" required>
                    </div>
                    <div class="mb-3">
                        <label for="userSurname" class="form-label">Sobrenome</label>
                        <input type="text" class="form-control" id="userSurname" required>
                    </div>
                    <div class="mb-3">
                        <label for="userAccessLevel" class="form-label">Nível de acesso</label>
                        <select class="form-select" id="userAccessLevel">
                            <option value="0" selected disabled hidden>Selecione um nível de acesso</option>
                            <?php foreach ($access_level['nivel_acesso'] as $access) : ?>
                                <option value="<?php echo $access['id']; ?>"><?php echo $access['nome']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="userBio" class="form-label">Biografia</label>
                        <textarea class="form-control" id="userBio" rows="8"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="userBirthday" class="form-label">Data de nascimento</label>
                        <input type="date" class="form-control" id="userBirthday" required>
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" required>
                        <p class="form-text text-danger d-none" id="userEmail-duplicate">Já existe um usuário cadastrado com esse email.</p>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="../usuarios" class="btn btn-outline-secondary">Voltar</a>
                        <input type="submit" class="btn btn-primary" value="Cadastrar">
                    </div>
                </form>

                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div class="toast align-items-center border border-success-subtle bg-success-subtle text-success" id="form-success" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <p>Usuário criado com sucesso.</p>
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?php echo $_ENV['BASE_URL']; ?>/dashboard/usuarios/create.js"></script>
        </main>
    </div>
</div>

<?php get_footer(); ?>