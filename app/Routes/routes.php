<?php

use App\Core\Router;
use App\Controllers\SiteController;
use App\Controllers\AuthController;
use App\Controllers\ErrorController;
use App\Controllers\ProfileController;

// --- Rotas Públicas ---

// Home
Router::get('/', [SiteController::class, 'home']);
Router::get('/home', [SiteController::class, 'home']);

// Páginas Institucionais
Router::get('/quem-somos', [SiteController::class, 'quemSomos']);
Router::get('/beneficios', [SiteController::class, 'beneficios']);

// Pesquisa
Router::get('/pesquisa', [SiteController::class, 'pesquisa']);
Router::get('/associados/api', [SiteController::class, 'pesquisa']); // API JSON

// --- Autenticação ---

Router::get('/login', [AuthController::class, 'login']);
Router::post('/login/auth', [AuthController::class, 'authenticate']);
Router::get('/logout', [AuthController::class, 'logout']);

// --- Área Logada (Perfil) ---

// Você precisará criar o ProfileController ou usar um placeholder por enquanto
// Router::get('/perfil', [ProfileController::class, 'index']);
// Router::get('/perfil/editar', [ProfileController::class, 'edit']);
// Router::post('/perfil/update', [ProfileController::class, 'update']);

// --- Erros ---
Router::get('/404', [ErrorController::class, 'notFound']);