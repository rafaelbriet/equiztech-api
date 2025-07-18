<div class="sidebar border border-right border-md-0 col-md-3 col-lg-2 p-0" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-md offcanvas-end" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">
                <img src="<?php get_image('logo_equiztech.svg'); ?>" alt="Logo Equiztech" height="32">
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Fechar"></button>
        </div>

        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3">
            <ul class="nav flex-column">
                <li class="navitem"><a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard" class="nav-link">Home</a></li>
                <li class="navitem"><a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/usuarios" class="nav-link">Usuários</a></li>
                <li class="navitem"><a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/categorias" class="nav-link">Categorias</a></li>
                <li class="navitem"><a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/quizzes" class="nav-link">Quizes</a></li>
            </ul>

            <hr class="my-1">

            <ul class="nav flex-column">
                <li class="navitem"><a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/me" class="nav-link">Meu perfil</a></li>
            </ul>

            <hr class="my-1">

            <ul class="nav flex-column">
                <li class="navitem"><a href="<?php echo $_ENV['BASE_URL']; ?>/dashboard/logout" class="nav-link">Sair</a></li>
            </ul>
        </div>
    </div>
</div>