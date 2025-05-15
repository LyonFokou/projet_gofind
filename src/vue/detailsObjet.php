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
    $idObjet = intval($_POST['idObjet']);

    // Requête pour supprimer le trajet
    $deleteSql = "DELETE FROM objet WHERE idObjet = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $idObjet);

    if ($stmt->execute()) {
        // Redirection vers la page mesObjets.php après suppression
        header("Location: mesObjetsDeclares.php");
        exit;
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// Récupérer l'ID du trajet depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Requête pour récupérer les informations du trajet
$sql = "SELECT * FROM objet WHERE idObjet = $id";
$result = $conn->query($sql);

// Vérifiez si le trajet existe
if ($result->num_rows > 0) {
    $objet = $result->fetch_assoc();
} else {
    echo "Objet non trouvé.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Objet - GoFind</title>
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
                <?php if (!empty($objet['photoObjet'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($objet['photoObjet']) ?>">
                <?php else: ?>
                    <div class="no-image">Pas d'image disponible</div>
                <?php endif; ?>
            </div>
            <div class="details">
                <br>

                <h2>INFORMATIONS SUR L'OBJET</h2><br>

                <div class="p-details">
                    <div class="info">
                        <p><strong>Marque :</strong> <?= htmlspecialchars($objet['marque']) ?></p><br>
                        <p><strong>Modele :</strong> <?= htmlspecialchars($objet['modele']) ?></p><br>
                        <p><strong>Numéro de série :</strong> <?= htmlspecialchars($objet['numeroSerie']) ?></p><br>
                        <p><strong>Type :</strong> <?= htmlspecialchars($objet['type']) ?></p><br>
                        <p><strong>Numéro de facture :</strong>
                            <?= htmlspecialchars($objet['numeroFacture']) ?></p><br>
                        <p><strong>Date d'achat :</strong> <?= htmlspecialchars($objet['dateAchat']) ?></p>
                    </div>
                    <form method="POST" action="">
        <input type="hidden" name="idObjet" value="<?= htmlspecialchars($objet['idObjet']) ?>">
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