<?php
require_once 'database.controller.php';

class UserController {
    private $pdo;

    public function __construct() {
        // Connexion via DatabaseController
        $this->pdo = DatabaseController::getConnection();
        
    }

    // Méthode pour ajouter un utilisateur
    public function ajouterUtilisateur($noms, $prenoms, $date_naissance, $sexe, $email, $telephone, $mot_de_passe) {
        try {

            $sql = "INSERT INTO utilisateur (noms, prenoms, dateNaissance, sexe, email, telephone, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$noms, $prenoms, $date_naissance, $sexe, $email, $telephone, $mot_de_passe]);

            return true;
        } catch (PDOException $e) {
            // Tu peux logger l'erreur ou la retourner
            return "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    

    // Méthode pour supprimer un utilisateur par ID
    public function supprimerUtilisateur($id) {
        try {
            $sql = "DELETE FROM utilisateur WHERE idUser = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            session_destroy();
        } catch (PDOException $e) {
            return "Erreur lors de la suppression : " . $e->getMessage();
        }
    }
    public function getInfo() {
        try {
            $idUser = $_SESSION['user_id'];
            $stmt = $this->pdo->prepare("SELECT* FROM utilisateur WHERE idUser = ?");
            $stmt->execute([$idUser]);
            $user = $stmt->fetch();
            return $user;
            
        } catch(PDOException $e) {
            return [];
        }
    }
   
    public function authentifier($email, $password) {
        session_start(); // Démarre la session
        $stmt = $this->pdo->prepare("SELECT idUser, password FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']){
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $user['idUser'];
            return true;
        }
        return false;
    }

    public function deconnecter() {
        session_start();
        session_unset();
        session_destroy();
    }
}
