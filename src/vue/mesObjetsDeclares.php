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
    <title>Mes Objets Declares - GoFind</title>
    <link rel="stylesheet" href="mesObjetsDeclares.css?v=3.0">
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
                <a href="monCompte.php" >Mon Profil</a><br>
                <a href="mesObjetsDeclares.php" class="active">Mes Objets déclarés</a><br>
                <a href="mesTrajets.php" >Mes annonces de Trajets</a><br>
                <a href="mesLogements.php">Mes annonces de Logements</a><br>
            </div>
            <button class="delete-account" onclick="openPopup()">Supprimer mon compte</button>
        </aside>

        <!-- route section -->
        <main class="profile">
            <div class="new-element-container">
                <button class="new-element" onclick="openForm()"> Ajouter une nouvelle annonce</button>
            </div>
            <h2>Vos objets declarés</h2>
            <!-- objet calcule dynamiquement-->
            <?php
            // Inclusion du contrôleur
            require_once '../controleur/my.object.controller.php';

            // Instanciation et récupération des données
            $controller = new ObjetController();
            $objets = $controller->getObjets();

            // Affichage conditionnel
            if (!empty($objets)):
                ?>
                <div class="objets-container">
                    <?php foreach ($objets as $objet): ?>
                        <a href="detailsObjet.php?id=<?= htmlspecialchars($objet['idObjet']) ?>">
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
                <p class="no-results">Aucun Objet enregistré pour le moment.</p>
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
        <form action="mesObjetsDeclares.traitement.php" method="post" enctype="multipart/form-data">
        <div class="form-content">
            <div class="input-group0">Nouvel Objet</div>
            <div class="input-picture">
                    <span>Photo de l'objet</span>
                    <div id="preview"></div>
                    <label class="custom-file-label" for="photo">Choisir une image</label>
                    <input type="file" id="photo" accept="image/*" name="photoObjet">
                </div>
            <div class="input-group">
                <label for="item1">Marque</label><br>
                <input type="text" id="item1" name="marque">
            </div>
            <div class="input-group">
                <label for="item2">Modèle</label><br>
                <input type="text" id="item2" name="modele">
            </div>
            <div class="input-group">
                <label for="item3">Numéro de série</label><br>
                <input type="text" id="item3" name="numeroSerie">
            </div>
            <div class="input-group">
                <label for="item4">Type</label><br>
                <select id="item4" name="type">
                    <option value=""></option>
                    <option value="smartphone">Smartphone</option>
                    <option value="laptop">Laptop</option>
                    <option value="power-bank">Power Bank</option>
                    <option value="ecouteur">Écouteur</option>
                    <option value="smartwatch">Smartwatch</option>
                </select>
            </div>
            <div class="input-group">
                <label for="item5">Numéro de facture</label><br>
                <input type="text" id="item5" name="numeroFacture">
            </div>
            <div class="input-group">
                <label for="item6">Date d'achat</label><br>
                <input type="date" id="item6" name="dateAchat">
            </div>
            <div id="close-btn-form-container">
                <div class="close-btn-form" onclick="closeForm()">Annuler</div>
            </div>
            <div>
                <input type="submit" class="btn-add" value="Ajouter" name="Ajouter">
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