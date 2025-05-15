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
    <title>Mes Trajets - GoFind</title>
    <link rel="stylesheet" href="mesTrajets.css?v=1.0">
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
                <a href="mesTrajets.php" class="active">Mes annonces de Trajets</a><br>
                <a href="mesLogements.php">Mes annonces de Logements</a><br>
            </div>
            <button class="delete-account" onclick="openPopup()">Supprimer mon compte</button>
        </aside>

        <!-- route section -->
        <main class="profile">
            <div class="new-element-container">
                <button class="new-element" onclick="openForm()"> Ajouter une nouvelle annonce</button>
            </div>
            <h2>Vos annonces de co-voiturage</h2>
            <?php
            // Inclusion du contrôleur
            require_once '../controleur/my.ride.controller.php';

            // Instanciation et récupération des données
            $controller = new TrajetController();
            $trajets = $controller->getTrajets();

            // Affichage conditionnel
            if (!empty($trajets)):
                ?>
                <div class="trajets-container">
                    <?php foreach ($trajets as $trajet): ?>
                        <a href="detailsTrajet.php?id=<?= htmlspecialchars($trajet['idTrajet']) ?>">
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
                <p class="no-results">Aucun trajet disponible pour le moment.</p>
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
        <form action="mesTrajets.traitement.php" method="POST" enctype="multipart/form-data">
            <div class="form-content">
                <div class="input-group0">Nouveau Trajet</div>
                <div class="input-picture">
                    <span>Photo du vehicule</span>
                    <div id="preview"></div>
                    <label class="custom-file-label" for="photo">Choisir une image</label>
                    <input type="file" id="photo" accept="image/*" name="photoVehicule">
                </div>
                <div class="input-group">
                    <label for="item1">Lieu de départ</label><br>
                    <input type="text" id="item1" name="lieuDepart">
                </div>
                <div class="input-group">
                    <label for="item2">Lieu d'arrivée</label><br>
                    <input type="text" id="item2" name="lieuArrivee">
                </div>
                <div class="input-group">
                    <label for="item3">Nombre de places</label><br>
                    <input type="number" id="item3" name="nombrePlace" min="1" max="100" step="1" value="1">
                </div>
                <div class="input-group">
                    <label for="item4">Type</label><br>
                    <select id="item4" name="type">
                        <option value=""></option>
                        <option value="transport-commun">Transport en commun</option>
                        <option value="vehicule-particulier">Véhicule de particulier</option>
                        <option value="agence-pro">Agence professionnelle</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="item5">Tarif</label><br>
                    <input type="text" id="item5" name="tarif">
                </div>
                <div class="input-group">
                    <label for="item4">Vehicule</label><br>
                    <select id="item4" name="vehicule">
                        <option value=""></option>
                        <option value="moto">Moto</option>
                        <option value="suv">SUV</option>
                        <option value="mini-bus">Mini-Bus</option>
                        <option value="bus">Bus</option>
                        <option value="berline">Berline</option>
                        <option value="camion">Camion</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="item6">Date et heure de départ</label><br>
                    <input type="datetime-local" id="item6" name="dateHeureDepart">
                </div>
                <div></div>
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
    <!-- deuxieme fenetre flottante -->

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