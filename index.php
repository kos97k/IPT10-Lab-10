<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

try {

    // Create Router instance
    $router = new \Bramus\Router\Router();

    // Define routes
    $router->get('/', '\App\Controllers\HomeController@index');
    $router->get('/suppliers', '\App\Controllers\SupplierController@list');
    $router->get('/suppliers/{id}', '\App\Controllers\SupplierController@single');
    $router->post('/suppliers/{id}', '\App\Controllers\SupplierController@update');
    
    // User registration and login routes
    $router->get('/registration-form', '\App\Controllers\RegistrationController@showForm');
    $router->post('/register', '\App\Controllers\RegistrationController@register');
    $router->get('/login-form', '\App\Controllers\LoginController@showForm');
    $router->post('/login', '\App\Controllers\LoginController@login');
    
    // Change this route to use HomeController@welcome
    $router->get('/welcome', '\App\Controllers\HomeController@welcome'); // Use HomeController for the welcome route
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    

    // Run it!
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
