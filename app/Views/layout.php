<?php

use App\Core\Auth;

// Garante que a sessão está iniciada para verificar o login
Auth::init();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ATCPE' ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/assets/img/favicon.png" sizes="32x32">

    <!-- Fontes e Ícones -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5 & CSS Personalizado -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/css/admin.css" rel="stylesheet"> -->
    <!-- 5. Seu CSS Personalizado -->
    <!-- <link href="<?= URL_BASE ?>/css/sidebar.css" rel="stylesheet"> -->
    <!-- <link href="<?= URL_BASE ?>/css/style.css" rel="stylesheet"> -->

    <?php if (isset($pageStyles) && is_array($pageStyles)): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/<?= $style ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<!-- Verifica via Auth se está logado para definir a classe do body -->

<body>

    <!-- 1. HEADER (Filho direto do 'div' de scroll) -->
    <?php
    // require_once __DIR__ . '/partials/header.php';
    ?>

    <?php require_once __DIR__ . '/partials/navbar.phtml'; ?>

    <!-- 2. CONTEÚDO PRINCIPAL -->
    <!-- <main class="container"> -->
        <?= $content ?>
    <!-- </main> -->

    <!-- 3. FOOTER -->
    <!-- Correção: Alterado de .php para .phtml para corresponder ao arquivo enviado -->
    <?php 
        if (file_exists(__DIR__ . '/partials/footer.phtml')) {
            require_once __DIR__ . '/partials/footer.phtml'; 
        } else {
            // Fallback caso o arquivo ainda seja .php no servidor
            require_once __DIR__ . '/partials/footer.php';
        }
    ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para Toggle do Sidebar (Importante para Mobile) -->
    <script>
        (function($) {
            "use strict";
            // Alternar sidebar
            $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
                $("body").toggleClass("sidebar-toggled");
                $(".sidebar").toggleClass("toggled");
                if ($(".sidebar").hasClass("toggled")) {
                    $('.sidebar .collapse').collapse('hide');
                };
            });
        })(jQuery);
    </script>

    <?php if (isset($pageScripts) && is_array($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="<?= defined('URL_BASE') ? URL_BASE : '' ?>/<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>