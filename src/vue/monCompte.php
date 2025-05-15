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
    <title>Mon Compte - GoFind</title>
    <link rel="stylesheet" href="monCompte.css?v= 2.0">
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

    <!-- fenetre principale-->
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Gérer mon compte</h2>
            <div class="sidebar_nav">
                <a href="monCompte.php" class="active">Mon Profil</a><br>
                <a href="mesObjetsDeclares.php">Mes Objets déclarés</a><br>
                <a href="mesTrajets.php">Mes annonces de Trajets</a><br>
                <a href="mesLogements.php">Mes annonces de Logements</a><br>
            </div>
            <button class="delete-account" onclick="openPopup()">Supprimer mon compte</button>
        </aside>
        <?php
            // Inclusion du contrôleur
            require_once '../controleur/user.controller.php';

            // Instanciation et récupération des données
            $controller = new UserController();
            $user = $controller->getInfo();

            ?>
        <!-- Profile Section -->
        <main class="profile">
            <h2>Votre profil</h2>
            <div class="profile-info">
                <div class="fields">
                    <label for="noms">Noms</label>
                    <input type="text" id="noms" value="<?= htmlspecialchars($user['noms']) ?> "readonly>

                    <label for="prenoms">Prénoms</label>
                    <input type="text" id="prenoms" value="<?= htmlspecialchars($user['prenoms']) ?>" readonly>

                    <label for="dateNaissance">Date de naissance</label>
                    <input type="text" id="dateNaissance" value="<?= htmlspecialchars($user['dateNaissance']) ?>" readonly>
                    
                    <label for="num_tel">Numéro de Téléphone</label>
                    <input type="text" id="num_tel" value="<?= htmlspecialchars($user['telephone']) ?>" readonly>
                
                    <label for="email">Adresse Email</label>
                    <input type="text" id="email" value="<?= htmlspecialchars($user['email']) ?>"readonly>

                    <label for="sexe">Sexe</label>
                    <input type="text" id="sexe" value="<?= htmlspecialchars($user['sexe']) ?>" readonly>
                </div>
                <div class="profile-picture">
                    <div class="picture-placeholder"></div>
                    <p>Photo de profil</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="logout.php">Déconnexion</a>
        </div>
       
    </footer>
    <!-- fenetre flottante-->
    <div id="popupOverlay" class="popup-overlay">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <img src="images/3.png">
            <p id="texte-popup">Êtes-vous sûr de vouloir supprimer votre compte ?</p>
            <P>L'opperation est irreversible</P>
            <form action="supprimerCompte.php" method="POST">
            <button type="submit" class="btn-submit" name="supprimer">confirmer</button>
            </form>
            
        </div>
    </div>

    <script>
        function openPopup() {
            document.getElementById("popupOverlay").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popupOverlay").style.display = "none";
        }

    </script>
</body>
</html>