<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;

class RegistrationController extends BaseController
{
    public function showForm()
    {
        $template = 'registration-form';
        $data = [
            'title' => 'User Registration'
        ];

        $output = $this->render($template, $data);

        return $output;
    }

    public function register()
    {
        // Validate input
        $errors = $this->validateRegistration($_POST);
        
        if (!empty($errors)) {
            $template = 'registration-form';
            $data = [
                'title' => 'User Registration',
                'errors' => $errors
            ];
            return $this->render($template, $data);
        }

        // Hash the password
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $user = new User();
        $data = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password_hash' => $passwordHash,
            'first_name' => $_POST['first_name'] ?? null,
            'last_name' => $_POST['last_name'] ?? null,
        ];

        $result = $user->register($data);

        if ($result) {
            $template = 'registration-success';
            $data = [
                'title' => 'Registration Successful',
                'message' => 'Successful Registration! Proceed to the <a href="/login-form">Login</a> form.'
            ];
        } else {
            $template = 'registration-form';
            $data = [
                'title' => 'User Registration',
                'errors' => ['Registration failed. Please try again.']
            ];
        }

        $output = $this->render($template, $data);

        return $output;
    }

    private function validateRegistration($data)
    {
        $errors = [];

        // Check required fields
        if (empty($data['username'])) {
            $errors[] = 'Username is required.';
        }
        if (empty($data['email'])) {
            $errors[] = 'Email is required.';
        }
        if (empty($data['password'])) {
            $errors[] = 'Password is required.';
        }
        if ($data['password'] !== $data['password_confirmation']) {
            $errors[] = 'Passwords do not match.';
        } else {
            // Validate password rules
            if (strlen($data['password']) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }
            if (!preg_match('/[0-9]/', $data['password'])) {
                $errors[] = 'Password must contain at least one numeric character.';
            }
            if (!preg_match('/[a-zA-Z]/', $data['password'])) {
                $errors[] = 'Password must contain at least one non-numeric character.';
            }
            if (!preg_match('/[!@#$%^&*-+]/', $data['password'])) {
                $errors[] = 'Password must contain at least one special character.';
            }
        }

        return $errors;
    }
}
