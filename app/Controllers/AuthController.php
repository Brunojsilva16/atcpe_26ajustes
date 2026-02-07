<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\AssociadoModel;

class AuthController extends BaseController
{
    private $associadoModel;

    public function __construct()
    {
        $this->associadoModel = new AssociadoModel();
    }

    public function login()
    {
        // Se já estiver logado, redireciona para o perfil
        if (Auth::check()) {
            $this->redirect('/perfil');
            return;
        }
        
        $this->render('pages/login', ['title' => 'Login - ATCPE']);
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password'); // 'senha' no form original, mas vamos padronizar

        if (!$email || !$password) {
            // Em MVC idealmente usamos Flash Messages para erros
            $this->redirect('/login?error=missing_fields'); 
            return;
        }

        $user = $this->associadoModel->findByEmail($email);

        // Verifica se usuário existe e se a senha bate
        // OBS: O código original usava password_verify. Mantendo o padrão.
        if ($user && password_verify($password, $user['senha'])) {
            
            // Verifica status (se necessário, baseado no código legado)
            if ($user['id_status'] != 1) {
                 $this->redirect('/login?error=inactive_account');
                 return;
            }

            // Cria a sessão usando a classe Auth do Core
            // Mapeando os campos do banco antigo para a sessão
            Auth::login([
                'id' => $user['id_associados'],
                'name' => $user['nomever'] ?? $user['nome'], // Usa nomever ou nome
                'email' => $user['email'],
                'role' => $user['user_tipo'], // 0 ou 1
                'photo' => $user['foto']
            ]);

            $this->redirect('/perfil');
        } else {
            $this->redirect('/login?error=invalid_credentials');
        }
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('/login');
    }
}