<?php

require('../functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

$authorization = 'Authorization: ' . $_COOKIE['TOKEN'];

$url = $_ENV['BASE_URL'] . '/api/usuarios/me.php';
$curl_handler = curl_init($url);
curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
$response_json = curl_exec($curl_handler);
curl_close($curl_handler);
$current_user = json_decode($response_json, true);

$url = $_ENV['BASE_URL'] . '/api/usuarios/?id=' . $current_user['usuario']['id'];
$curl_handler = curl_init($url);
curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
$response_json = curl_exec($curl_handler);
curl_close($curl_handler);
$user = json_decode($response_json, true);

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">
            <?php if (isset($user['erro'])) : ?>
                <p class="border border-danger-subtle bg-danger-subtle text-danger p-2 rounded mt-3"><?php echo $user['erro']['mensagem']; ?></p>
            <?php else : ?>
                <div class="d-flex justify-content-between flex-wrap flex-md-no-wrap align-items-center border-bottom pt-3 pb-2 mb-3">
                    <h1 class="h2">Meu perfil</h1>
                </div>

                <div class="mb-3">
                    <form class="form-dashboard">
                        <input type="hidden" class="form-control" id="userId" value="<?php echo $user['usuario']['id']; ?>">
                        <input type="hidden" class="form-control" id="personalDataId" value="<?php echo $user['usuario']['id_dados_pessoais']; ?>">
                        <input type="hidden" class="form-control" id="userAccessLevel" value="<?php echo $user['usuario']['id_nivel_acesso']; ?>">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="userName" value="<?php echo $user['usuario']['nome']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="userSurname" class="form-label">Sobrenome</label>
                            <input type="text" class="form-control" id="userSurname" value="<?php echo $user['usuario']['sobrenome']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="userBio" class="form-label">Bio</label>
                            <textarea class="form-control" id="userBio" rows="8"><?php echo $user['usuario']['biografia']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="userBirthday" class="form-label">Data de nascimento</label>
                            <input type="date" class="form-control" id="userBirthday" value="<?php echo $user['usuario']['data_nascimento']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" value="<?php echo $user['usuario']['email']; ?>" required>
                            <p class="form-text text-danger d-none" id="userEmail-duplicate">Já existe um usuário cadastrado com esse email.</p>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard" class="btn btn-outline-secondary">Cancelar</a>
                            <input type="submit" class="btn btn-primary" value="Atualizar">
                        </div>
                    </form>

                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                        <div class="toast align-items-center border border-success-subtle bg-success-subtle text-success" id="form-success" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <p>Usuário atualizado com sucesso.</p>
                                </div>
                                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="<?php echo $_ENV['BASE_URL']; ?>/dashboard/me/me.js"></script>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>