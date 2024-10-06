<?php

require('../functions.php');

only_admin_allowed();
get_header();
get_partial('nav');

?>

<script src="<?php echo $_ENV['BASE_URL']; ?>/dashboard/logout/index.js"></script>

<?php get_footer(); ?>