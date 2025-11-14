<?php
// Inclure le fichier de connexion à la base de données
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';


try {
    // Récupérer les données des candidats depuis la base de données
    $candidates = get_candidats($connexion);

    // Envoyer les données au format JSON
    header('Content-Type: application/json');
    echo json_encode($candidates);
} catch (PDOException $e) {
    // En cas d'erreur de la base de données, afficher l'erreur
    echo json_encode(array('error' => 'Erreur de la base de données : ' . $e->getMessage()));
}
?>
