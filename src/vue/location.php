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
    <title>GoFind - Annonces de co-location</title>
    <link rel="stylesheet" href="location.css?v=2.0" />
</head>

<body>
    <div id="top-band"></div>
    <header class="header">
        <div class="logo">GoFind</div>
        <div class="nav">
            <a href="Home.php">Accueil</a>
            <a href="objets.php" >Objets</a>
            <a href="trajets.php">Trajets</a>
            <a href="location.php" class="active">Location</a>
            <a href="monCompte.php">Mon compte</a>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <form action="location.php" method="get">
                <div class="filter-header">
                    <h3>Filtres</h3>
                </div>
                <hr>
                <div class="filter-header">Type </div>
                    <select id="item4" name="titre" class="filter-content-list">
                        <option value=""></option>
                        <option value="appartement">Appartement</option>
                        <option value="maison">Maison</option>
                        <option value="studio">Studio</option>
                        <option value="chambre">Chambre</option>
                    </select>
                <hr>
                <label for="adresse" class="filter-header">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="filter-content-list" />
                <hr>
                <label for="superficieTotale" class="filter-header">Superficie totale</label>
                <input type="number" id="superficieTotale" name="superficieTotale" class="filter-content-list" />
                <hr>
                <label for="tarif" class="filter-header">Tarif</label>
                <input type="number" id="tarif" name="tarif" class="filter-content-list" />
                <hr>
                <label for="nombrePiece" class="filter-header">Nombre de Piece</label>
                <input type="number" id="nombrePiece" name="nombrePiece" class="filter-content-list" min="0" value="0"/>
                <hr>
                    
                <button type="submit" class="apply-btn">Appliquer les Filtres</button>
            </form>
        </aside>

        <main class="main">
            <h1>Rechercher une colocation - Louer en toute simplicité !</h1>
            <div class="cards" id="cards-container">
                <?php
                require_once '../controleur/database.controller.php';
                try {
                    // Connexion à la base de données avec PDO
                    $pdo = DatabaseController::getConnection();

                    // Construire la requête SQL avec des paramètres
                    $sql = "SELECT * FROM logement WHERE 1=1";
                    $params = [];

                    if (!empty($_GET['titre'])) {
                        $sql .= " AND titre = :titre";
                        $params[':titre'] = $_GET['titre'];
                    }

                    if (!empty($_GET['adresse'])) {
                        $sql .= " AND adresse = :adresse";
                        $params[':adresse'] = $_GET['adresse'];
                    }
                    if (!empty($_GET['superficieTotale'])) {
                        $sql .= " AND superficieTotale = :superficieTotale";
                        $params[':superficieTotale'] = $_GET['superficieTotale'];
                    }


                    if (!empty($_GET['tarif'])) {
                        $sql .= " AND tarif = :tarif";
                        $params[':tarif'] = $_GET['tarif'];
                    }
                    if (!empty($_GET['nombrePiece'])) {
                        $sql .= " AND nombrePiece = :nombrePiece";
                        $params[':nombrePiece'] = $_GET['nombrePiece'];
                        
                    }


                    // Préparer et exécuter la requête
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $logements = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Vérifier si des trajets existent
                    if (!empty($logements)):
                        ?>
                        <div class="logement-container">
                            <?php foreach ($logements as $l): ?>
                                <a href="detailsLogement2.php?id=<?= htmlspecialchars($l['idLogement']) ?>">
                                <div class="logement-card">
                                    
                                        <!-- Ajout de l'image -->
                                        <div class="logement-image">
                                            <?php if (!empty($l['photoLogement'])): ?>
                                                <img
                                                    src="data:<?= htmlspecialchars($l['photoLogement']) ?>;base64,<?= base64_encode($l['photoLogement']) ?>">
        
                                            <?php else: ?>
                                                <div class="no-image">Pas d'image disponible</div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="logement-detail">
                                            <strong>Titre :</strong> <?= htmlspecialchars($l['titre']) ?>
                                        </div>
                                        <div class="logement-detail">
                                            <strong>Adresse :</strong> <?= htmlspecialchars($l['adresse']) ?>
                                        </div>
                                        <div class="logement-detail">
                                            <strong>Superficie totale :</strong> <?= htmlspecialchars($l['superficieTotale']) ?>
                                        </div>
                                        <div class="logement-detail">
                                            <strong>Tarif :</strong> <?= htmlspecialchars($l['tarif']) ?>
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