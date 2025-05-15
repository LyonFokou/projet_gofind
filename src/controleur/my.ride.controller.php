<?php
require_once 'database.controller.php';

class TrajetController {
    private $pdo;

    public function __construct() {
        // Connexion via DatabaseController
        $this->pdo = DatabaseController::getConnection();
    }

    // Méthode pour ajouter un utilisateur
    public function ajouterTrajet($lieuD, $lieuA, $nonmbreP, $tarif, $type, $vehicule, $dateHeureD,$photoV,$idUser) {
        try {

            $sql = "INSERT INTO trajet (lieuDepart, lieuArrivee,nombrePlace, tarif, type, vehicule, dateHeureDepart,photoVehicule,idUser)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$lieuD, $lieuA, $nonmbreP, $tarif, $type, $vehicule, $dateHeureD,$photoV,$idUser]);

            return true;
        } catch (PDOException $e) {
            // Tu peux logger l'erreur ou la retourner
            return "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    

    // Méthode pour supprimer un utilisateur par ID
    public function supprimerTrajet($id) {
        try {
            $sql = "DELETE FROM trajet WHERE idTrajet = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            return true;
        } catch (PDOException $e) {
            return "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    public function getTrajets() {
        $idUser = $_SESSION['user_id'];
        try {
            $stmt = $this->pdo->prepare("SELECT* FROM trajet WHERE idUser = ?");
            $stmt->execute([$idUser]);
            $trajet = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $trajet;
            
            
        } catch(PDOException $e) {
            return [];
        }
    }
}