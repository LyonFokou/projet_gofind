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
    $idLogement = intval($_POST['idLogement']);

    // Requête pour supprimer le trajet
    $deleteSql = "DELETE FROM logement WHERE idLogement = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $idLogement);

    if ($stmt->execute()) {
        // Redirection vers la page mesTrajets.php après suppression
        header("Location: mesLogements.php");
        exit;
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// Récupérer l'ID du trajet depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Requête pour récupérer les informations du trajet
$sql = "SELECT * FROM logement WHERE idLogement = $id";
$result = $conn->query($sql);

// Vérifiez si le trajet existe
if ($result->num_rows > 0) {
    $logement = $result->fetch_assoc();
} else {
    echo "Logement non trouvé.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Logement - GoFind</title>
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
                <?php if (!empty($logement['photoLogement'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($logement['photoLogement']) ?>">
                <?php else: ?>
                    <div class="no-image">Pas d'image disponible</div>
                <?php endif; ?>
            </div>
            <div class="details">
                <br>

                <h2>INFORMATIONS SUR LE LOGEMENT</h2><br>

                <div class="p-details">
                    <div class="info">
                        <p><strong>Titre :</strong> <?= htmlspecialchars($logement['titre']) ?></p><br>
                        <p><strong>Adresse :</strong> <?= htmlspecialchars($logement['adresse']) ?></p><br>
                        <p><strong>Superficie totale :</strong> <?= htmlspecialchars($logement['superficieTotale']) ?></p><br>
                        <p><strong>Tarif :</strong> <?= htmlspecialchars($logement['tarif']) ?> XAF</p><br>
                        <p><strong>Nombre de pièces :</strong>
                            <?= htmlspecialchars($logement['nombrePiece']) ?></p><br>
                        <p><strong>Description :</strong> <?= htmlspecialchars($logement['description']) ?></p>
                    </div>
                    <form method="POST" action="">
        <input type="hidden" name="idLogement" value="<?= htmlspecialchars($logement['idLogement']) ?>">
        <input type="submit" class="btn-submit" name="delete" value="Supprimer l'annonce">
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