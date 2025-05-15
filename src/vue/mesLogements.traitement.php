<?php
session_start();
if(!isset($_SESSION['auth'])||$_SESSION['auth'] !== true){
    header("Location: index.html");
    exit();
}
?>
<?php
require_once '../controleur/my.home.controller.php';
session_start();
    $idUser = $_SESSION['user_id'];
    try {
            
    
        // Récupération des données du formulaire
        $titre = $_POST['titre'];
        $adresse = $_POST['adresse'];
        $superficie= $_POST['superficieTotale'];
        $tarif = $_POST['tarif'];
        $nombrePiece = $_POST['nombrePiece'];
        $description = $_POST['description'];
        $photoLogement = file_get_contents($_FILES['photoLogement']['tmp_name']);
        
    
        // Requête préparée 
        $homeController = new HomeController();
        $state = $homeController->ajouterLogement($titre, $adresse, $superficie, $tarif, $nombrePiece, $description, $photoLogement,$idUser);
        header("Location: mesLogements.php");
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
