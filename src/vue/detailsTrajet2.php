<?php
session_start();
if(!isset($_SESSION['auth'])||$_SESSION['auth'] !== true){
    header("Location: index.html");
    exit();
}
?>
<?php

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'gofindbd');

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier si une suppression est demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $idTrajet = intval($_POST['idTrajet']);

    // Requête pour supprimer le trajet
    $deleteSql = "DELETE FROM trajet WHERE idTrajet = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $idTrajet);

    if ($stmt->execute()) {
        // Redirection vers la page mesTrajets.php après suppression
        header("Location: mesTrajets.php");
        exit;
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// Récupérer l'ID du trajet depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Requête pour récupérer les informations du trajet
$sql = "SELECT * FROM trajet WHERE idTrajet = $id";
$result = $conn->query($sql);

// Vérifiez si le trajet existe
if ($result->num_rows > 0) {
    $trajet = $result->fetch_assoc();
} else {
    echo "Trajet non trouvé.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Trajets - GoFind</title>
    <link rel="stylesheet" href="details.css">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">GoFind</div>
        <div class="nav">
            <a href="home.php">Accueil</a>
            <a href="objets.php">Objets</a>
            <a href="trajets.php">Trajets</a>
            <a href="location.php">Location</a>
            <a href="monCompte.php" id="header-active">Mon compte</a>
        </div>
    </header>


    <main>

        <main class="container">
            <div class="image-section">
                <?php if (!empty($trajet['photoVehicule'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($trajet['photoVehicule']) ?>">
                <?php else: ?>
                    <div class="no-image">Pas d'image disponible</div>
                <?php endif; ?>
            </div>
            <div class="details">
                <br>

                <h2>INFORMATIONS SUR LE TRAJET</h2><br>

                <div class="p-details">
                    <div class="info">
                        <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['lieuDepart']) ?></p><br>
                        <p><strong>Destination :</strong> <?= htmlspecialchars($trajet['lieuArrivee']) ?></p><br>
                        <p><strong>Nombre de places :</strong> <?= htmlspecialchars($trajet['nombrePlace']) ?></p><br>
                        <p><strong>Tarif :</strong> <?= htmlspecialchars($trajet['tarif']) ?> XAF</p><br>
                        <p><strong>Date et heure de départ :</strong>
                            <?= htmlspecialchars($trajet['dateHeureDepart']) ?></p><br>
                        <p><strong>Type de véhicule :</strong> <?= htmlspecialchars($trajet['vehicule']) ?></p><br>
                        <p><strong>Type de trajet :</strong> <?= htmlspecialchars($trajet['type']) ?></p>
                    </div>
                    <form method="POST" action="">
        <input type="hidden" name="idTrajet" value="<?= htmlspecialchars($trajet['idTrajet']) ?>">
        <input type="submit" class="btn-submit" name="delete" value="s'associer au trajet">
    </form>
                </div>

            </div>
            
            </div>
        </main>




    </main>

    <!-- Footer -->
    <footer class="footer"></footer>

</body>

</html>