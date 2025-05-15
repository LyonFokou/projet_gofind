<?php
require_once '../controleur/user.controller.php';

$controller = new UserController();

$email = $_POST['email'];
$password = $_POST['password'];


if (($controller->authentifier($email, $password))==1) {
    // Redirection après connexion
    header('Location: home.php'); 
    exit();
} else {
    header('Location: index.html');
}
?>