<?php
// update_resultats.php
// Inclure les fonctions pour récupérer les données des candidats
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';


try {
    // Récupérer les informations des candidats depuis la base de données
    $candidats = get_candidats($connexion);

    // Construire le HTML pour afficher les candidats
    $html = '';
    foreach ($candidats as $candidat) {
        $html .= '<div class="col-md-2">';
        $html .= '<div class="card" style="height: 200px;">';
        $html .= '<img src="img/' . $candidat['abbr'] . '.jpg" class="card-img-top" alt="' . $candidat['candidat'] . '" style="max-width: 200px; max-height: 50px;">';
        $html .= '<div class="card-body">';
        $html .= '<h6 class="card-title" style="height: 50px;">' . $candidat['candidat'] . '</h6>';
        $html .= '<p class="card-text small candidatID" id="' . $candidat['id'] . '">'; 
        $html .= 'Voix : ' . $candidat['total_voix'] . ' / ' . $candidat['pourcentage'] . '%</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    }

    // Envoyer le HTML mis à jour
    echo $html;
} catch (PDOException $erreur) {
    echo "Erreur lors de la mise à jour des résultats : " . $erreur->getMessage();
}
?>
