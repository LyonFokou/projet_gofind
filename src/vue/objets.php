<?php
session_start();
if(!isset($_SESSION['auth'])||$_SESSION['auth'] !== true){
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GoFind - Objets déclarés comme volé</title>
    <link rel="stylesheet" href="objets.css?v=2.0" />
</head>

<body>
    <div id="top-band"></div>
    <header class="header">
        <div class="logo">GoFind</div>
        <div class="nav">
            <a href="Home.php">Accueil</a>
            <a href="objets.php" class="active">Objets</a>
            <a href="trajets.php">Trajets</a>
            <a href="location.php">Location</a>
            <a href="monCompte.php">Mon compte</a>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <form action="objets.php" method="get">
                <div class="filter-header">
                    <h3>Filtres</h3>
                </div>
                <hr>
                <label for="marque" class="filter-header">Marque</label>
                <input type="text" id="marque" name="marque" class="filter-content-list" />
                <hr>
                <label for="modele" class="filter-header">Modele</label>
                <input type="text" id="modele" name="modele" class="filter-content-list" />
                <hr>
                <label for="numeroSerie" class="filter-header">Numéro de série</label>
                <input type="text" id="numeroSerie" name="numeroSerie" class="filter-content-list" />
                <hr>
                <div class="filter-section" id="filter-vehicle">
                    <div class="filter-header">Type </div>
                    <select id="item4" name="type" class="filter-content-list" >
                    <option value=""></option>
                    <option value="smartphone">Smartphone</option>
                    <option value="laptop">Laptop</option>
                    <option value="power-bank">Power Bank</option>
                    <option value="ecouteur">Écouteur</option>
                    <option value="smartwatch">Smartwatch</option>
                </select>
                </div>
                <hr>
                <label for="numeroFacture" class="filter-header">Numéro de facture</label>
                <input type="text" id="numeroFacture" name="numeroFacture" class="filter-content-list" />
                <hr>
                <button type="submit" class="apply-btn">Appliquer les Filtres</button>
            </form>
        </aside>

        <main class="main">
            <h1>Rechercher un Objet volé - Acheter en toute séréinité !</h1>
            <div class="cards" id="cards-container">
                <?php
                require_once '../controleur/database.controller.php';
                try {
                    // Connexion à la base de données avec PDO
                    $pdo = DatabaseController::getConnection();

                    // Construire la requête SQL avec des paramètres
                    $sql = "SELECT * FROM objet WHERE 1=1";
                    $params = [];

                    if (!empty($_GET['marque'])) {
                        $sql .= " AND marque = :marque";
                        $params[':marque'] = $_GET['marque'];
                    }

                    if (!empty($_GET['modele'])) {
                        $sql .= " AND modele = :modele";
                        $params[':modele'] = $_GET['modele'];
                    }
                    if (!empty($_GET['numeroSerie'])) {
                        $sql .= " AND numeroSerie = :numeroSerie";
                        $params[':numeroSerie'] = $_GET['numeroSerie'];
                    }


                    if (!empty($_GET['numeroFacture'])) {
                        $sql .= " AND numeroFacture = :numeroFacture";
                        $params[':numeroFacture'] = $_GET['numeroFacture'];
                    }
                    if (!empty($_GET['type'])) {
                        $sql .= " AND type = :type";
                        $params[':type'] = $_GET['type'];
                        
                    }


                    // Préparer et exécuter la requête
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $objets = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Vérifier si des trajets existent
                    if (!empty($objets)):
                        ?>
                        <div class="objets-container">
                            <?php foreach ($objets as $objet): ?>
                                <a href="detailsObjet2.php?id=<?= htmlspecialchars($objet['idObjet']) ?>">
                                <div class="objet-card">
                                    
                                        <!-- Ajout de l'image -->
                                        <div class="objet-image">
                                            <?php if (!empty($objet['photoObjet'])): ?>
                                                <img
                                                    src="data:<?= htmlspecialchars($objet['photoObjet']) ?>;base64,<?= base64_encode($objet['photoObjet']) ?>">
        
                                            <?php else: ?>
                                                <div class="no-image">Pas d'image disponible</div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="objet-detail">
                                            <strong>Marque :</strong> <?= htmlspecialchars($objet['marque']) ?>
                                        </div>
                                        <div class="objet-detail">
                                            <strong>Modele :</strong> <?= htmlspecialchars($objet['modele']) ?>
                                        </div>
                                        <div class="objet-detail">
                                            <strong>Numéro de série :</strong> <?= htmlspecialchars($objet['numeroSerie']) ?>
                                        </div>
                                        <div class="objet-detail">
                                            <strong>Type :</strong> <?= htmlspecialchars($objet['type']) ?>
                                        </div>
                                </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-results">Aucun résultat.</p>
                    <?php endif;
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer"></footer>
</body>

</html>