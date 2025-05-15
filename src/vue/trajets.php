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
  <title>GoFind - Trajets de covoiturage</title>
  <link rel="stylesheet" href="trajets.css?v=1.0" />
</head>

<body>
  <div id="top-band"></div>
  <header class="header">
    <div class="logo">GoFind</div>
    <div class="nav">
      <a href="Home.php">Accueil</a>
      <a href="objets.php">Objets</a>
      <a href="trajets.php" class="active">Trajets</a>
      <a href="location.php">Location</a>
      <a href="monCompte.php">Mon compte</a>
    </div>
  </header>

  <div class="container">
    <aside class="sidebar">
      <form action="trajets.php" method="get">
        <div class="filter-section" id="filter-categories">
          <div class="filter-header">
            <h3>Filtres</h3>
          </div>
        </div>
        <hr>
        <div class="filter-section" id="filter-price">
          <div class="filter-header">Prix</div>
          <div class="filter-content">
            <div class="slider-group">
              <div class="filter-content-title">Maximum</div><br>
        
              <input type="number" id="max-price" min="0" max="50000" value="50000" name="prixMax" class="filter-content-list"/>
            </div>
            <div class="slider-group">
              <div class="filter-content-title">Minimum</div><br>
              <input type="number" id="min-price" min="0" max="50000" value="0" name="prixMin" class="filter-content-list"/>
            </div>
          </div>
        </div>
        <hr>
        <div class="filter-section" id="filter-vehicle">
          <div class="filter-header">Véhicule </div>
          <div class="filter-content">
            <select id="item4" name="vehicule" class="filter-content-list">
              <option value=""></option>
              <option value="moto">Moto</option>
              <option value="suvp">SUV</option>
              <option value="mini-bus">Mini-Bus</option>
              <option value="bus">Bus</option>
              <option value="berline">Berline</option>
              <option value="camion">Camion</option>
            </select>
          </div>
        </div>
        <hr>
        <div class="filter-section" id="filter-details">
          <div class="filter-header">Lieu</div>
          <div class="filter-content">
             <label for="destination-list" class="filter-content-title">Depart</label><br>
            <input type="text" name="lieuDepart" id="destination-list" class="filter-content-list" />
            <br>
            <label for="destination-list" class="filter-content-title">Destination</label><br>
            <input type="text" name="lieuArrivee" id="destination-list" class="filter-content-list" />
            <br>
          </div>
        </div>


        <button type="submit" class="apply-btn">Appliquer les Filtres</button>
      </form>
    </aside>

    <main class="main">
      <h1>Rechercher un trajet - Voyager en toute sécurité</h1>
      <div class="cards" id="cards-container">
        <?php
        require_once '../controleur/database.controller.php';
        try {
          // Connexion à la base de données avec PDO
          $pdo = DatabaseController::getConnection();

          // Construire la requête SQL avec des paramètres
          $sql = "SELECT * FROM trajet WHERE 1=1";
          $params = [];

          if (!empty($_GET['lieuDepart'])) {
            $sql .= " AND lieuDepart = :lieuDepart";
            $params[':lieuDepart'] = $_GET['lieuDepart'];
          }

          if (!empty($_GET['lieuArrivee'])) {
            $sql .= " AND lieuArrivee = :lieuArrivee";
            $params[':lieuArrivee'] = $_GET['lieuArrivee'];
          }

          if (!empty($_GET['prixMin'])) {
            $sql .= " AND tarif >= :prixMin";
            $params[':prixMin'] = intval($_GET['prixMin']);
          }

          if (!empty($_GET['prixMax'])) {
            $sql .= " AND tarif <= :prixMax";
            $params[':prixMax'] = intval($_GET['prixMax']);
          }
          if (!empty($_GET['vehicule'])) {
            $sql .= " AND vehicule = :vehicule";
            $params[':vehicule'] = $_GET['vehicule'];
          }


          // Préparer et exécuter la requête
          $stmt = $pdo->prepare($sql);
          $stmt->execute($params);
          $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Vérifier si des trajets existent
          if (!empty($trajets)):
            ?>
            <div class="trajets-container">
              <?php foreach ($trajets as $trajet): ?>
                <a href="detailsTrajet2.php?id=<?= htmlspecialchars($trajet['idTrajet']) ?>">
                  <div class="trajet-card">

                    <!-- Ajout de l'image -->
                    <div class="trajet-image">
                      <?php if (!empty($trajet['photoVehicule'])): ?>
                        <img
                          src="data:<?= htmlspecialchars($trajet['photoVehicule']) ?>;base64,<?= base64_encode($trajet['photoVehicule']) ?>">

                      <?php else: ?>
                        <div class="no-image">Pas d'image disponible</div>
                      <?php endif; ?>
                    </div>
                    <div class="trajet-detail">
                      <strong>Départ :</strong> <?= htmlspecialchars($trajet['lieuDepart']) ?>
                    </div>
                    <div class="trajet-detail">
                      <strong>Destination :</strong> <?= htmlspecialchars($trajet['lieuArrivee']) ?>
                    </div>
                    <div class="trajet-detail">
                      <strong>Nombre de place :</strong> <?= htmlspecialchars($trajet['nombrePlace']) ?>
                    </div>
                    <div class="trajet-detail">
                      <strong>Tarif :</strong> <?= htmlspecialchars($trajet['tarif']) ?>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="no-results">Aucun resultat !</p>

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