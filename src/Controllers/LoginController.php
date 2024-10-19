<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;

class LoginController extends BaseController
{
    public function showForm()
{
    session_start();

    // Check if user is already logged in
    if (isset($_SESSION['is_logged_in'])) {
        header('Location: /welcome');
        exit;
    }

    // Lockout duration in seconds (5 minutes = 300 seconds)
    $lockoutDuration = 300;

    // Check if the user is locked out
    if (isset($_SESSION['lockout_time'])) {
        $timeLeft = ($_SESSION['lockout_time'] + $lockoutDuration) - time();

        if ($timeLeft > 0) {
            // If still locked out, display a message and disable the form
            $disabled = true;
            $attempts = 0;
            $data = [
                'title' => 'User Login',
                'disabled' => $disabled,
                'lockout_time' => $timeLeft
            ];

            return $this->render('login-form', $data);
        } else {
            // Lockout expired, reset attempts
            $_SESSION['attempts'] = 0;
            unset($_SESSION['lockout_time']);
        }
    }

    // Regular login form with remaining attempts logic
    $disabled = isset($_SESSION['attempts']) && $_SESSION['attempts'] >= 3;
    $attempts = $disabled ? 0 : 3 - ($_SESSION['attempts'] ?? 0);

    $data = [
        'title' => 'User Login',
        'disabled' => $disabled,
        'attempts' => $attempts
    ];

    return $this->render('login-form', $data);
}

public function login()
{
    session_start();
    
    // Initialize attempts
    $_SESSION['attempts'] = $_SESSION['attempts'] ?? 0;

    if ($_SESSION['attempts'] >= 3) {
        // If locked out, set the lockout time if not already set
        if (!isset($_SESSION['lockout_time'])) {
            $_SESSION['lockout_time'] = time(); // Store current time as lockout start time
        }
        header('Location: /login-form');
        exit;
    }

    $user = new User();
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        // Successful login
        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $username;
        $_SESSION['attempts'] = 0;
        unset($_SESSION['lockout_time']); // Clear lockout time on successful login
        header('Location: /welcome');
        exit;
    } else {
        // Failed login
        $_SESSION['attempts']++;
        if ($_SESSION['attempts'] >= 3) {
            $_SESSION['lockout_time'] = time(); // Set lockout time after 3 failed attempts
        }
        header('Location: /login-form');
        exit;
    }
}


    public function logout()
    {
        session_start();
        $_SESSION = [];   
        session_destroy();
        header('Location: /login-form');
        exit;
    }

    
}
