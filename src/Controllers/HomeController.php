<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Supplier;
use App\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        $template = 'home';
        $data = [
            'student' => 'GABRIEL LORENZO S. JAVIERTO',
            'title' => 'IPT10 Laboratory Activity #10',
            'college' => 'College of Computer Studies',
            'university' => 'Angeles University Foundation',
            'location' => 'Angeles City, Pampanga, Philippines'
        ];
        $output = $this->render($template, $data);
        return $output;
    }

    public function welcome()
    {
        session_start();
    
        if (!isset($_SESSION['is_logged_in'])) {
            header('Location: /login-form');
            exit;
        }
    
        $userModel = new User();
        $users = $userModel->getAllUsers();
    
        $template = 'welcome';
        $data = [
            'users' => $users
        ];
    
        return $this->render($template, $data);
    }
}