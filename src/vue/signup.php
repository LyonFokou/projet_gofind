<?php
require_once '../controleur/user.controller.php';

    try {
    
        // Récupération des données du formulaire
        $noms = $_POST['noms'];
        $prenoms = $_POST['prenoms'];
        $date_naissance = $_POST['date_naissance'];
        $sexe = $_POST['sexe'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $mot_de_passe = $_POST['mot_de_passe'];
    
        // Requête préparée 
        $userController = new UserController();
        $userController->ajouterUtilisateur($noms, $prenoms, $date_naissance, $sexe, $email, $telephone, $mot_de_passe);
        header("Location: index.html?display= block ");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    

?>