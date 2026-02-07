<?php
// app/Controllers/HomeController.php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\HomeModel;
use App\Core\Paginator;

class HomeController extends BaseController
{
    public function index()
    {
        date_default_timezone_set('America/Sao_Paulo');
        Auth::init(); 

        if (!Auth::isLogged()) {
            $this->redirect('/login');
            return;
        }

        $HomeModel = new HomeModel();

        $data = [
            'view' => 'home',
            'title' => 'Página Inicial',
            // 'user' => Auth::getUser(),
        ];

        $this->view('pages/home', $data);
    }
}