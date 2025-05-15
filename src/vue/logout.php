<?php
session_start();
$_SESSION = []; // Vide toutes les variables de session

// Détruit le cookie de session si présent
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // Détruit la session
header("Location: index.php");
exit();
?>