<?php
require_once 'database.controller.php';

class HomeController {
    private $pdo;

    public function __construct() {
        // Connexion via DatabaseController
        $this->pdo = DatabaseController::getConnection();
    }

    // Méthode pour ajouter un utilisateur
    public function ajouterLogement($titre, $adresse, $superficie, $tarif, $nbP, $description, $photoL,$idUser) {
        try {

            $sql = "INSERT INTO logement (titre, adresse,superficieTotale, tarif, nombrePiece, description,photoLogement,idUser)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$titre, $adresse, $superficie, $tarif, $nbP, $description, $photoL,$idUser]);

            return true;
        } catch (PDOException $e) {
            // Tu peux logger l'erreur ou la retourner
            return "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    

    // Méthode pour supprimer un utilisateur par ID
    public function supprimerLogement($id) {
        try {
            $sql = "DELETE FROM logement WHERE idLogement = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            return true;
        } catch (PDOException $e) {
            return "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    public function getLogement() {
        $idUser = $_SESSION['user_id'];
        try {
            $stmt = $this->pdo->prepare("SELECT* FROM logement WHERE idUser = ?");
            $stmt->execute([$idUser]);
            $logement = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $logement;
            
            
        } catch(PDOException $e) {
            return [];
        }
    }
}