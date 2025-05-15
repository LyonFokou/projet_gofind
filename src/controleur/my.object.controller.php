<?php
require_once 'database.controller.php';

class ObjetController {
    private $pdo;

    public function __construct() {
        // Connexion via DatabaseController
        $this->pdo = DatabaseController::getConnection();
    }

    // Méthode pour ajouter un utilisateur
    public function ajouterObjet($marque, $modele, $numeroSerie, $type, $numeroFacture, $dateAchat,$photoObjet,$idUser) {
        try {

            $sql = "INSERT INTO objet (marque, modele, numeroSerie, type, numeroFacture, dateAchat,photoObjet,idUser)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$marque, $modele, $numeroSerie, $type, $numeroFacture, $dateAchat,$photoObjet,$idUser]);

            return true;
        } catch (PDOException $e) {
            // Tu peux logger l'erreur ou la retourner
            return "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    

    // Méthode pour supprimer un utilisateur par ID
    public function supprimerTrajet($id) {
        try {
            $sql = "DELETE FROM objet WHERE idObjet = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            return true;
        } catch (PDOException $e) {
            return "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    public function getObjets() {
        $idUser = $_SESSION['user_id'];
        try {
            $stmt = $this->pdo->prepare("SELECT* FROM objet WHERE idUser = ?");
            $stmt->execute([$idUser]);
            $objet = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $objet;
            
            
        } catch(PDOException $e) {
            return [];
        }
    }
}