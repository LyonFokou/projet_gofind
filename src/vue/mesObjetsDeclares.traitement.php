<?php
session_start();
if(!isset($_SESSION['auth'])||$_SESSION['auth'] !== true){
    header("Location: index.html");
    exit();
}
?>
<?php
require_once '../controleur/my.object.controller.php';
session_start();
    $idUser = $_SESSION['user_id'];
    try {
            
    
        // Récupération des données du formulaire
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $numeroSerie= $_POST['numeroSerie'];
        $type = $_POST['type'];
        $numeroFacture = $_POST['numeroFacture'];
        $dateAchat = $_POST['dateAchat'];
        $photoObjet = file_get_contents($_FILES['photoObjet']['tmp_name']);
        //echo $photoVehicule;
    
        // Requête préparée 
        $objetController1 = new ObjetController();
        $state = $objetController1->ajouterObjet($marque,$modele,$numeroSerie,$type,$numeroFacture,$dateAchat,$photoObjet,$idUser);
        header("Location: mesObjetsDeclares.php");
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
