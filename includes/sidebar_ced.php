<?php
// Menu de l'administrateur pour un département
// Appeler la fonction pour récupérer les départements
$communes = get_departement_communes($connexion, $_SESSION['did']);

if ($communes) {
    // Afficher le menu latéral avec les régions et leurs départements
    include 'sidebar_comp_head.php';

// Boucler à travers les départements pour afficher chaque région et ses départements
$currentDepartement = '';
foreach ($communes as $commune) {
    $departement = $commune['departement'];
    $communeNom = $commune['commune'];
    $communeId = $commune['cid'];
    $departementId = $commune['did'];

    // Vérifier si la région a changé
    if ($departement !== $currentDepartement) {
        // Si c'est le cas, afficher la nouvelle région
        if ($currentDepartement !== '') {
            // Fermer la liste des départements précédente
            echo '</ul>';
            echo '</li>';
        }

        // Ouvrir une nouvelle liste pour la nouvelle région
        echo '<li class="nav-item bg-white">';
        echo '<div class="d-flex nav-link justify-content-between">';
        echo '<a href="#" class="d-flex nav-link align-items-center" data-toggle="collapse" data-target="#' . strtolower($departement) . '">' . $departement . '</a>';
        echo '</div>';
        

        echo '<ul id="' . strtolower($departement) . '" class="collapse">';
        // Mettre à jour la région actuelle
        $currentDepartement = $departement;
    }

    // Afficher le département
    echo '<li class="nav-item">';
    echo '<div class="d-flex nav-link justify-content-between">';
    echo '<a class="nav-link d-flex justify-content-between dep-selection" href="#" id="'.$communeId.'">' . $communeNom . '</a>';
    echo '</div>';
    echo '</li>';
    
}
    // Fermer la liste des départements pour la dernière région
    echo '</ul>';
    echo '</li>';

} else {
    // Si aucun département n'a été trouvé, afficher un message d'erreur
    echo "Aucun département trouvé.";
}
?>
