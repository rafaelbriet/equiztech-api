<?php

require('../functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

$category_id = $_GET['id'];
$url = $_ENV['BASE_URL'] . '/api/usuarios/?id=' . $category_id;
$curl_handler = curl_init($url);
curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
$authorization = 'Authorization: ' . $_COOKIE['TOKEN'];
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
$response_json = curl_exec($curl_handler);
curl_close($curl_handler);
$response = json_decode($response_json, true);

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">

            <?php if (isset($response['erro'])) : ?>
                <p class="border border-danger-subtle bg-danger-subtle text-danger p-2 rounded mt-3"><?php echo $response['erro']['mensagem']; ?></p>
            <?php else : ?>
                <div class="d-flex justify-content-between flex-wrap flex-md-no-wrap align-items-center border-bottom pt-3 pb-2 mb-3">
                    <h1 class="h2">Deletar usuário</h1>
                </div>

                <div class="mb-3">
                    <p>Você realmente deseja deletar o usuário "<?php echo "{$response['usuario']['nome']} {$response['usuario']['sobrenome']} ({$response['usuario']['email']})"; ?>?</p>
                    <p>Esta ação não pode ser desfeita.</p>

                    <form class="form-dashboard">
                        <div class="mb-3">
                            <input type="hidden" class="form-control" id="categoryId" value="<?php echo $response['usuario']['id']; ?>">
                            <p class="form-text text-danger d-none" id="categoryName-duplicate">Não foi possível deletar o usuário no momento. Tente novamente.</p>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-outline-secondary">Voltar</button>
                            <input type="submit" class="btn btn-danger" value="Deletar">
                        </div>
                    </form>

                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                        <div class="toast align-items-center border border-danger-subtle bg-danger-subtle text-danger" id="form-success" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <p>Usuário deletetado com sucesso.</p>
                                </div>
                                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>