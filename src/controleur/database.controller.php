<?php
class DatabaseController {
    private static $pdo = null;

    // Empêche l’instanciation directe
    private function __construct() {}

    // Méthode pour obtenir l’instance PDO
    public static function getConnection() {
        if (self::$pdo === null) {
            $host = 'localhost';
            $dbname = 'gofindbd';
            $username = 'root';
            $password = '';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}