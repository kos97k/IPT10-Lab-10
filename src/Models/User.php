<?php

namespace App\Models;

use PDO;

class User
{
    private $db;

    public function __construct()
    {
        // Initialize the database connection
        $this->db = new PDO('mysql:host=localhost;dbname=IPT10LAB10', 'root', 'Javierto123$');
    }

    public function register($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['username'], $data['email'], $data['password_hash'], $data['first_name'], $data['last_name']]);
    }

    public function login($username, $password)
    {
        $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return true;
        }
        return false;
    }

    public function getAllUsers()
{
    $stmt = $this->db->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
