<?php

namespace App\Core;

class Router
{
    private static $routes = [];

    public static function get($url, $controller)
    {
        self::$routes['GET'][$url] = $controller;
    }

    public static function post($url, $controller)
    {
        self::$routes['POST'][$url] = $controller;
    }

    public static function dispatch()
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(self::$routes[$method][$url])) {
            $action = self::$routes[$method][$url];

            // Se for string 'Controller@metodo' (Formato Antigo)
            if (is_string($action)) {
                $parts = explode('@', $action);
                $controllerName = "App\\Controllers\\" . $parts[0];
                $methodName = $parts[1];
            } 
            // Se for array [Controller::class, 'metodo'] (Formato Novo)
            elseif (is_array($action)) {
                $controllerName = $action[0];
                $methodName = $action[1];
            } else {
                die("Formato de rota inválido.");
            }

            // Verifica se a classe existe
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                } else {
                    die("Método {$methodName} não encontrado no controller {$controllerName}");
                }
            } else {
                die("Controller {$controllerName} não encontrado");
            }
        } else {
            // Tratamento simples de 404
            if (isset(self::$routes['GET']['/404'])) {
                $errorAction = self::$routes['GET']['/404'];
                // Repete a lógica simplificada para o erro (assumindo formato string pro fallback)
                $parts = is_string($errorAction) ? explode('@', $errorAction) : $errorAction; 
                $cName = is_string($errorAction) ? "App\\Controllers\\" . $parts[0] : $parts[0];
                $mName = is_string($errorAction) ? $parts[1] : $parts[1];
                
                $c = new $cName();
                $c->$mName();
            } else {
                echo "404 - Página não encontrada";
            }
        }
    }
}