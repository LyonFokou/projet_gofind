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
    <title>Mes Logements- GoFind</title>
    <link rel="stylesheet" href="mesLogements.css?v=3.0">
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
                <a href="monCompte.php">Mon Profil</a><br>
                <a href="mesObjetsDeclares.php">Mes Objets déclarés</a><br>
                <a href="mesTrajets.php">Mes annonces de Trajets</a><br>
                <a href="mesLogements.php" class="active">Mes annonces de Logements</a><br>
            </div>
            <button class="delete-account" onclick="openPopup()">Supprimer mon compte</button>
        </aside>

        <!-- route section -->
        <main class="profile">
            <div class="new-element-container">
                <button class="new-element" onclick="openForm()"> Ajouter une nouvelle annonce</button>
            </div>
            <h2>Vos annonces de co-location</h2>
            <?php
            // Inclusion du contrôleur
            require_once '../controleur/my.home.controller.php';

            // Instanciation et récupération des données
            $controller = new HomeController();
            $logements = $controller->getLogement();

            // Affichage conditionnel
            if (!empty($logements)):
                ?>
                <div class="logement-container">
                    <?php foreach ($logements as $logement): ?>
                        <a href="detailsLogement.php?id=<?= htmlspecialchars($logement['idLogement']) ?>">
                            <div class="logement-card">

                                <!-- Ajout de l'image -->
                                <div class="logement-image">
                                    <?php if (!empty($logement['photoLogement'])): ?>
                                        <img
                                            src="data:<?= htmlspecialchars($logement['photoLogement']) ?>;base64,<?= base64_encode($logement['photoLogement']) ?>">

                                    <?php else: ?>
                                        <div class="no-image">Pas d'image disponible</div>
                                    <?php endif; ?>
                                </div>
                                <div class="logement-detail">
                                    <strong>Titre :</strong> <?= htmlspecialchars($logement['titre']) ?>
                                </div>
                                <div class="logement-detail">
                                    <strong>Adresse :</strong> <?= htmlspecialchars($logement['adresse']) ?>
                                </div>
                                <div class="logement-detail">
                                    <strong>Superficie totale :</strong> <?= htmlspecialchars($logement['superficieTotale']) ?>
                                </div>
                                <div class="logement-detail">
                                    <strong>Tarif :</strong> <?= htmlspecialchars($logement['tarif']) ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-results">Aucun logement disponible pour le moment.</p>
            <?php endif; ?>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="logout.php">Déconnexion</a>
        </div>

    </footer>
    <!-- formulaire ajout-->
    <div id="form-container" class="form-container">
        <form action="mesLogements.traitement.php" method="post" enctype="multipart/form-data">
            <div class="form-content">
                <div class="input-group0">Nouveau Logement</div>
                <div class="input-picture">
                    <span>Photo du vehicule</span>
                    <div id="preview"></div>
                    <label class="custom-file-label" for="photo">Choisir une image</label>
                    <input type="file" id="photo" accept="image/*" name="photoLogement">
                </div>
                <div class="input-group">
                    <label for="item4">Titre</label>
                    <select id="item4" name="titre" >
                        <option value=""></option>
                        <option value="appartement">Appartement</option>
                        <option value="maison">Maison</option>
                        <option value="studio">Studio</option>
                        <option value="chambre">Chambre</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="item2">Adresse</label><br>
                    <input type="text" id="item2" name="adresse">
                </div>
                <div class="input-group">
                    <label for="item3">Superficie Totale</label><br>
                    <input type="text" id="item3" name="superficieTotale">
                </div>

                <div class="input-group">
                    <label for="item4">Tarif</label><br>
                    <input type="text" id="item4" name="tarif">
                </div>
                <div class="input-group">
                    <label for="item5">Nombre de Pièces</label><br>
                    <input type="number" id="item5" min="1" max="20" step="1" value="1" name="nombrePiece">
                </div>
                <div class="input-group">
                    <label for="item6">Description</label><br>
                    <input type="text" id="item6" name="description">
                </div>
                <div id="close-btn-form-container">
                    <div class="close-btn-form" onclick="closeForm()">Annuler</div>
                </div>
                <div>
                    <input type="submit" class="btn-add" value="Ajouter" name="ajouter">
                </div>
            </div>
        </form>
    </div>
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
        // affichage dynamique des images dans le formulaire
        const input = document.getElementById('photo');
        const preview = document.getElementById('preview');
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.style.backgroundImage = `url('${e.target.result}')`;
                    preview.textContent = "";
                }
                reader.readAsDataURL(file);
            }
        });
        //ouverture et fermeture des fenetres flotantes
        function openPopup() {
            document.getElementById("popupOverlay").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popupOverlay").style.display = "none";
        }
        function openPopup2() {
            document.getElementById("popupOverlay2").style.display = "block";
        }

        function closePopup2() {
            document.getElementById("popupOverlay2").style.display = "none";
        }
        function openForm() {
            document.getElementById("form-container").style.display = "block";
        }

        function closeForm() {
            document.getElementById("form-container").style.display = "none";
        }

    </script>
</body>

</html>