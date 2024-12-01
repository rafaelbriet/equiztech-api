<?php

require('functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white mb-3">

            <?php

                $url = $_ENV['BASE_URL'] . '/api/analytics/';
                $curl_handler = curl_init($url);
                curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
                curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
                $authorization = 'Authorization: ' . $_COOKIE['TOKEN'];
                curl_setopt($curl_handler, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
                $response_json = curl_exec($curl_handler);
                curl_close($curl_handler);
                $response = json_decode($response_json, true);

                function display_stat($name) {
                    global $response;
                    echo $response[$name];
                }
            ?>

            <div class="row">
                <div class="d-flex justify-content-between flex-wrap flex-md-no-wrap align-items-center border-bottom pt-3 pb-2 mb-3">
                    <h2 class="h2">Dashboard</h2>
                </div>
            </div>

            <!-- USUÁRIOS -->
            <div class="row">
                <h3 class="fs-5">Usuários</h3>
                <div class="cards d-flex flex-column flex-md-row g-2" style="gap: 16px">
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Usuários</p>
                            <p><?php display_stat('total_usuarios'); ?></p>
                        </div>
                    </div>
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Administradores</p>
                            <p><?php display_stat('total_administradores'); ?></p>
                        </div>
                    </div>
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Jogadores</p>
                            <p><?php display_stat('total_jogadores'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIM USUÁRIOS -->

            <!-- CATEGORIAS -->
            <div class="row mt-3">
                <h3 class="fs-5">Categorias</h3>
                <div class="cards d-flex flex-column flex-md-row g-2" style="gap: 16px">
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Categorias</p>
                            <p><?php display_stat('total_categorias'); ?></p>
                        </div>
                    </div>
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Perguntas</p>
                            <p><?php display_stat('total_perguntas'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <th>Total de Perguntas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($response['total_pergunta_por_categoria']) > 0) : ?>
                                <?php foreach ($response['total_pergunta_por_categoria'] as $item) : ?>
                                    <tr>
                                        <td><?php echo $item['nome_categoria']; ?></td>
                                        <td><?php echo $item['total_perguntas']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="2">Nenhuma categoria cadastrada.</td>
                                </tr>
                            <?php endif ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- FIM CATEGORIAS -->

            <!-- PARTIDAS -->
            <div class="row">
                <h3 class="fs-5">Partidas</h3>
                <div class="cards d-flex flex-column flex-md-row g-2" style="gap: 16px">
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Partidas</p>
                            <p><?php display_stat('total_partidas'); ?></p>
                        </div>
                    </div>
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Perguntas Respondidas</p>
                            <p><?php display_stat('total_perguntas_respondidas'); ?></p>
                        </div>
                    </div>
                    <div class="card flex-md-fill">
                        <div class="card-body">
                            <p class="card-title">Total de Perguntas Respondidas Corretamente</p>
                            <p><?php display_stat('total_perguntas_respondidas_corretamente'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIM PARTIDAS -->
        </main>
    </div>
</div>

<?php get_footer(); ?>