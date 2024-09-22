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
                <h1 class="h2">Usuários</h1>
                <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Adicionar</a>
            </div>

            <?php

            $url = $_ENV['BASE_URL'] . '/api/usuarios/';
            $curl_handler = curl_init($url);
            curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
            curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
            $authorization = 'Authorization: ' . $_COOKIE['TOKEN'];
            curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
            $response_json = curl_exec($curl_handler);
            curl_close($curl_handler);
            $response = json_decode($response_json, true);

            ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome completo</th>
                        <th>Email</th>
                        <th>Data de nascimento</th>
                        <th>Nível de acesso</th>
                        <th>Editar</th>
                        <th>Exluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($response['usuarios'] as $user) : ?>
                        <tr>
                            <td><?php echo $user['nome'] . ' ' . $user['sobrenome']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['data_nascimento']; ?></td>
                            <td><?php echo $user['nome_nivel_acesso']; ?></td>
                            <td>
                                <a href="edit.php/?id=<?php echo $user['id']; ?>" class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                            <td>
                                <a href="delete.php/?id=<?php echo $user['id']; ?>" class="btn btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<?php get_footer(); ?>