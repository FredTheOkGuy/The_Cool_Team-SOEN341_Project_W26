<?php
// This is the register/login page functionality
session_start();
require_once __DIR__ . '/../../config/login_page_config.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../models/sql_meals_functions.php';
require_once __DIR__ . '/../models/sql_login_register_functions.php';


use ParagonIE\AntiCSRF\AntiCSRF;

$csrf = new AntiCSRF(); 
$valid = false;

if (isset($_POST['login'])) {
    $valid = $csrf->validateRequest('login_form');
} elseif (isset($_POST['register'])) {
    $valid = $csrf->validateRequest('register_form');
}

if (!$valid) {
    die("Invalid CSRF token. Request blocked.");
}

// If you are registering (click the register button)
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_retype = $_POST['retype_password'];

    // Make sure the email isn't already registered
    registerAccount($conn, $name, $email, $password, $password_retype);

    header("Location: " . BASE_URL . "/index.php");
    exit();
}

// This is for when the user clicks the login button
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    loginAccount($conn, $email, $password);

    // If its wrong, then warning
    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: " . BASE_URL . "/index.php");
    exit();
}
?>