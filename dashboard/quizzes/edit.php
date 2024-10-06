<?php

require('../functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

$authorization = 'Authorization: ' . $_COOKIE['TOKEN'];

$question_id = $_GET['id'];
$url = $_ENV['BASE_URL'] . '/api/perguntas/?id=' . $question_id;
$curl_handler = curl_init($url);
curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
$response_json = curl_exec($curl_handler);
curl_close($curl_handler);
$question = json_decode($response_json, true);

$url = $_ENV['BASE_URL'] . '/api/categorias/';
$curl_handler = curl_init($url);
curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
$response_json = curl_exec($curl_handler);
curl_close($curl_handler);
$categories = json_decode($response_json, true);

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">
            <?php if (isset($question['erro'])) : ?>
                <p class="border border-danger-subtle bg-danger-subtle text-danger p-2 rounded mt-3"><?php echo $question['erro']['mensagem']; ?></p>
            <?php else : ?>
                <div class="d-flex justify-content-between flex-wrap flex-md-no-wrap align-items-center border-bottom pt-3 pb-2 mb-3">
                    <h1 class="h2">Editar quiz</h1>
                </div>

                <div class="mb-3">
                    <form class="form-dashboard">
                        <input type="hidden" class="form-control" id="quizId" value="<?php echo $question['pergunta']['id']; ?>">
                        <div class="mb-3">
                            <label for="quizQuestion" class="form-label">Pergunta</label>
                            <textarea class="form-control" id="quizQuestion" rows="4"><?php echo $question['pergunta']['texto_pergunta'] ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="quizDescription" class="form-label">Explicação</label>
                            <textarea class="form-control" id="quizDescription" rows="8"><?php echo $question['pergunta']['explicacao'] ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="quizCategoria" class="form-label">Categoria</label>
                            <select class="form-select" id="quizCategoria">
                                <option value="0" selected disabled hidden>Selecione uma categoria</option>
                                <?php foreach ($categories['categorias'] as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $question['pergunta']['id_categoria'] ? 'selected' : '' ?>><?php echo $category['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quizStatus" class="form-label">Status</label>
                            <select class="form-select" id="quizStatus">
                                <option value="0" <?php echo $question['pergunta']['ativo'] == 0 ? 'selected' : '' ?>>Desativado</option>
                                <option value="1" <?php echo $question['pergunta']['ativo'] == 1 ? 'selected' : '' ?>>Ativo</option>
                            </select>
                        </div>

                        <?php for ($i = 0; $i < count($question['pergunta']['respostas']); $i++) : ?>
                            <?php $answer = $question['pergunta']['respostas'][$i]; ?>
                            <div class="mb-3">
                                <label for="quizAnswer<?php echo $i + 1; ?>" class="form-label">Resposta <?php echo $i + 1; ?></label>
                                <div class="d-flex gap-2">
                                    <input type="hidden" class="form-control" id="quizAnswerId<?php echo $i + 1; ?>" value="<?php echo $answer['id']; ?>">
                                    <input type="radio" name="quizAnswer" id="quizAnswerCorrect<?php echo $i + 1; ?>" <?php echo $answer['correta'] == 1 ? 'checked' : ''; ?>>
                                    <input type="text" class="form-control" id="quizAnswer<?php echo $i + 1; ?>" class="ml-2" value="<?php echo $answer['texto_alternativa'] ?>" required>
                                </div>
                            </div>
                        <?php endfor; ?>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/quizzes" class="btn btn-outline-secondary">Cancelar</a>
                            <input type="submit" class="btn btn-primary" value="Cadastrar">
                        </div>
                    </form>

                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                        <div class="toast align-items-center border border-success-subtle bg-success-subtle text-success" id="form-success" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <p>Quiz atualizado com sucesso.</p>
                                </div>
                                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="<?php echo $_ENV['BASE_URL']; ?>/dashboard/quizzes/edit.js"></script>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>