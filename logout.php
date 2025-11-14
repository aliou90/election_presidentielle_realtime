<?php
// Démarre la session
session_start();

// Détruit toutes les variables de session
$_SESSION = array();

// Si vous voulez détruire complètement la session, effacez également la session du disque
// Note : cela détruira la session et pas seulement les données de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruit la session
session_destroy();

// Redirige vers la page de connexion ou toute autre page appropriée après la déconnexion
header("Location: login.php");
exit();
?>
