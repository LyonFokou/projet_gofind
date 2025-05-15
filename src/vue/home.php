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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil - GoFind</title>
    <link rel="stylesheet" href="home.css?v=2.0">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">GoFind</div>
        <div class="nav">
            <a href="home.php" id="header-active">Accueil</a>
            <a href="objets.php">Objets</a>
            <a href="trajets.php">Trajets</a>
            <a href="location.php">Location</a>
            <a href="monCompte.php" >Mon compte</a>
        </div>
    </header>

    <!-- fenetre principale-->
    <div class="container">
       <main class="home-main">
        <section class="home-section colocation">
            <div class="overlay">
                <p>Découvrez la colocation : trouvez ou proposez un logement partagé facilement.</p>
                <a href="location.php" class="home-btn">Voir plus</a>
            </div>
        </section>
        <section class="home-section covoiturage">
            <div class="overlay">
                <p>Optez pour le co-voiturage : partagez vos trajets et réduisez vos frais de déplacement.</p>
                <a href="trajets.php" class="home-btn">Voir plus</a>
            </div>
        </section>
        <section class="home-section objets">
            <div class="overlay">
                <p>Objets volés : signalez ou consultez les objets perdus ou volés dans votre région.</p>
                <a href="objets.php" class="home-btn">Voir plus</a>
            </div>
        </section>
    </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="logout.php">Déconnexion</a>
        </div>
       
    </footer>
    <!-- fenetre flottante-->
    
</body>
</html>