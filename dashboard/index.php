<?php

require('functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

?>

<div class="container-fluid">
    <div class="row">
        <?php get_partial('sidebar'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-white">
        </main>
    </div>
</div>

<?php get_footer(); ?>