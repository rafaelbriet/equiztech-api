<?php

require('../functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">

            <div class="d-flex justify-content-between flex-wrap flex-md-no-wrap align-items-center border-bottom pt-3 pb-2 mb-3">
                <h1 class="h2">Quizzes</h1>
                <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Adicionar</a>
            </div>

            <?php

            $url = $_ENV['BASE_URL'] . '/api/perguntas/';
            $curl_handler = curl_init($url);
            curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
            curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
            $authorization = 'Authorization: ' . $_COOKIE['TOKEN'];
            curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
            $response_json = curl_exec($curl_handler);
            curl_close($curl_handler);
            $response = json_decode($response_json, true);

            ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pergunta</th>
                            <th>Categoria</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($response['perguntas'] as $category) : ?>
                            <tr>
                                <td><?php echo $category['texto_pergunta']; ?></td>
                                <td><?php echo $category['nome_categoria']; ?></td>
                                <td><?php echo $category['ativo']; ?></td>
                                <td>
                                    <a href="edit.php/?id=<?php echo $category['id']; ?>" class="btn btn-secondary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="delete.php/?id=<?php echo $category['id']; ?>" class="btn btn-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>