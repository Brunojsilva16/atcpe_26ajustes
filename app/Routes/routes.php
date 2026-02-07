<?php

use App\Core\Router;
use App\Controllers\SiteController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ErrorController;

// --- ROTAS PÚBLICAS ---
Router::get('/', [SiteController::class, 'home']);
Router::get('/home', [SiteController::class, 'home']);
Router::get('/quem-somos', [SiteController::class, 'quemSomos']);
Router::get('/beneficios', [SiteController::class, 'beneficios']);
Router::get('/pesquisa', [SiteController::class, 'pesquisa']);
Router::get('/associados/api', [SiteController::class, 'pesquisa']); // API JSON

// --- AUTH ---
Router::get('/login', [AuthController::class, 'login']);
Router::post('/login/auth', [AuthController::class, 'authenticate']);
Router::get('/logout', [AuthController::class, 'logout']);

// --- ERROS ---
Router::get('/404', [ErrorController::class, 'notFound']);