<?php
session_start();
if(!isset($_SESSION['auth'])||$_SESSION['auth'] !== true){
    header("Location: index.html");
    exit();
}
?>
<?php
require_once '../controleur/my.ride.controller.php';
require_once '../controleur/user.controller.php';
session_start();
    $idUser = $_SESSION['user_id'];
    try {
            
    
        // Récupération des données du formulaire
        $lieuDepart = $_POST['lieuDepart'];
        $lieuArrivee = $_POST['lieuArrivee'];
        $NombrePlace = $_POST['nombrePlace'];
        $tarif = $_POST['tarif'];
        $type = $_POST['type'];
        $vehicule = $_POST['vehicule'];
        $dateHeureDepart = $_POST['dateHeureDepart'];
        $photoVehicule = file_get_contents($_FILES['photoVehicule']['tmp_name']);
        //echo $photoVehicule;
    
        // Requête préparée 
        $trajetController1 = new TrajetController();
        $state = $trajetController1->ajouterTrajet($lieuDepart,$lieuArrivee,$NombrePlace,$tarif,$type,$vehicule,$dateHeureDepart,$photoVehicule,$idUser);
        header("Location: mesTrajets.php");
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

