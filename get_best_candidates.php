<?php
// Inclure le fichier de connexion à la base de données
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';


try {
    // Récupérer les meilleurs candidats
    $candidates = best_candidats($connexion);

    // Convertir les données en JSON et les renvoyer
    echo json_encode($candidates);
} catch (PDOException $e) {
    // En cas d'erreur de la base de données, afficher l'erreur
    echo 'Erreur de la base de données : ' . $e->getMessage();
}
?>
