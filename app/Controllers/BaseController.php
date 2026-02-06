<?php

namespace App\Controllers;

abstract class BaseController
{
    /**
     * Renderiza uma view dentro do layout principal.
     *
     * @param string $viewPath Caminho da view (ex: 'pages/home')
     * @param array $data Dados a serem disponibilizados na view (ex: ['title' => 'Home'])
     */
    protected function render(string $viewPath, array $data = [])
    {
        // 1. Extrai os dados do array para variáveis soltas (ex: $data['title'] vira $title)
        extract($data);

        // 2. Define o diretório base das views
        $viewsDir = __DIR__ . '/../Views/';

        // 3. Resolve o caminho completo do arquivo da view
        // Tenta achar .php, se não achar, tenta .phtml (comum no seu projeto antigo)
        $filePath = $viewsDir . $viewPath;
        
        if (file_exists($filePath . '.php')) {
            $filePath .= '.php';
        } elseif (file_exists($filePath . '.phtml')) {
            $filePath .= '.phtml';
        } else {
            die("Erro: View não encontrada em {$filePath}.php ou .phtml");
        }

        // 4. Captura o conteúdo da view específica (Buffering)
        // Isso permite que o HTML da página (home, login, etc) seja salvo na variável $content
        // para ser exibido no lugar correto dentro do layout.php
        ob_start();
        require $filePath;
        $content = ob_get_clean();

        // 5. Carrega o Layout Principal
        // O arquivo layout.php deve dar um "echo $content;" onde o corpo da página deve aparecer
        $layoutPath = $viewsDir . 'layout.php';
        
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            // Fallback se não tiver layout: imprime o conteúdo direto
            echo $content;
        }
    }

    /**
     * Redireciona para uma URL específica
     */
    protected function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}