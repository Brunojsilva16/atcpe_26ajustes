<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ATCPE' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Bootstrap e outros CSS que vieram do projeto antigo -->
</head>
<body>

    <!-- Inclui o Navbar -->
    <?php require_once __DIR__ . '/partials/navbar.phtml'; ?>

    <!-- AQUI É ONDE A MÁGICA ACONTECE -->
    <!-- A variável $content vem do BaseController::render() -->
    <main class="container">
        <?= $content ?>
    </main>

    <!-- Inclui o Footer -->
    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="/assets/js/scripts.js"></script>
</body>
</html>