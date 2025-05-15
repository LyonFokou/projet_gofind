<?php
require_once '../controleur/user.controller.php';

session_start();
$idUser = $_SESSION['user_id'];
$userController = new UserController();
$userController->supprimerUtilisateur($idUser);
session_destroy();
header('Location: signup.html?display= block');
exit();