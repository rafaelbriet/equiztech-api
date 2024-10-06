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
                <h1 class="h2">Adicionar categoria</h1>
            </div>

            <div class="mb-3">
                <form class="form-dashboard">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nome da categoria</label>
                        <input type="text" class="form-control" id="categoryName" required>
                        <p class="form-text text-danger d-none" id="categoryName-duplicate">Já existe uma categoria cadastrada com esse nome.</p>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/categorias" class="btn btn-outline-secondary">Cancelar</a>
                        <input type="submit" class="btn btn-primary" value="Cadastrar">
                    </div>
                </form>

                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div class="toast align-items-center border border-success-subtle bg-success-subtle text-success" id="form-success" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <p>Categoria criada com sucesso.</p>
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="create.js"></script>

<?php get_footer(); ?>