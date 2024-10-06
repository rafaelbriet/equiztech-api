<?php

require('../functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

$url = $_ENV['BASE_URL'] . '/api/categorias/';
$curl_handler = curl_init($url);
curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
$authorization = 'Authorization: ' . $_COOKIE['TOKEN'];
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
$response_json = curl_exec($curl_handler);
curl_close($curl_handler);
$categories = json_decode($response_json, true);

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">
            <div class="d-flex justify-content-between flex-wrap flex-md-no-wrap align-items-center border-bottom pt-3 pb-2 mb-3">
                <h1 class="h2">Cadastrar quiz</h1>
            </div>

            <div class="mb-3">
                <?php if (!empty($categories['categorias'])) : ?>
                    <form class="form-dashboard">
                        <div class="mb-3">
                            <label for="quizQuestion" class="form-label">Pergunta</label>
                            <textarea class="form-control" id="quizQuestion" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="quizDescription" class="form-label">Explicação</label>
                            <textarea class="form-control" id="quizDescription" rows="8"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="quizCategoria" class="form-label">Categoria</label>
                            <select class="form-select" id="quizCategoria">
                                <option value="0" selected disabled hidden>Selecione uma categoria</option>
                                <?php foreach ($categories['categorias'] as $categorie) : ?>
                                    <option value="<?php echo $categorie['id']; ?>"><?php echo $categorie['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quizStatus" class="form-label">Status</label>
                            <select class="form-select" id="quizStatus">
                                <option value="-1" selected disabled hidden>Selecione uma status</option>
                                <option value="0">Desativado</option>
                                <option value="1">Ativo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quizAnswer1" class="form-label">Resposta 1</label>
                            <div class="d-flex gap-2">
                                <input type="radio" name="quizAnswer" id="quizAnswerCorrect1" checked>
                                <input type="text" class="form-control" id="quizAnswer1" class="ml-2" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quizAnswer2" class="form-label">Resposta 2</label>
                            <div class="d-flex gap-2">
                                <input type="radio" name="quizAnswer" id="quizAnswerCorrect2">
                                <input type="text" class="form-control" id="quizAnswer2" class="ml-2" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quizAnswer3" class="form-label">Resposta 3</label>
                            <div class="d-flex gap-2">
                                <input type="radio" name="quizAnswer" id="quizAnswerCorrect3">
                                <input type="text" class="form-control" id="quizAnswer3" class="ml-2" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quizAnswer4" class="form-label">Resposta 4</label>
                            <div class="d-flex gap-2">
                                <input type="radio" name="quizAnswer" id="quizAnswerCorrect4">
                                <input type="text" class="form-control" id="quizAnswer4" class="ml-2" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/quizzes" class="btn btn-outline-secondary">Cancelar</a>
                            <input type="submit" class="btn btn-primary" value="Cadastrar">
                        </div>
                        </form>
                <?php else : ?>
                    <p>Nenhuma categoria cadastrada.</p>
                    <p><a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/categorias">Cadastre um categoria</a> para criar um quiz.</p>
                <?php endif; ?>

                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div class="toast align-items-center border border-success-subtle bg-success-subtle text-success" id="form-success" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <p>Quiz criado com sucesso.</p>
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?php echo $_ENV['BASE_URL']; ?>/dashboard/quizzes/create.js"></script>
        </main>
    </div>
</div>

<?php get_footer(); ?>