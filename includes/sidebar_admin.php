<?php
// Menu de l'administrateur pour ajouter des régions, départements, communes, lieux de vote, bureaux et candidats
// Appeler la fonction pour récupérer les départements
$departements = get_departement($connexion);

if ($departements) {
    // Afficher le menu latéral avec les régions et leurs départements
    include 'sidebar_comp_head.php';

// Boucler à travers les départements pour afficher chaque région et ses départements
$currentRegion = '';
foreach ($departements as $departement) {
    $region = $departement['region'];
    $departementNom = $departement['departement'];
    $departementId = $departement['did'];
    $regionId = $departement['rid'];

    // Vérifier si la région a changé
    if ($region !== $currentRegion) {
        // Si c'est le cas, afficher la nouvelle région
        if ($currentRegion !== '') {
            // Fermer la liste des départements précédente
            echo '</ul>';
            echo '</li>';
        }

        // Ouvrir une nouvelle liste pour la nouvelle région
        echo '<li class="nav-item bg-white">';
        echo '<div class="d-flex nav-link justify-content-between">';
        echo '<a href="#" class="d-flex nav-link align-items-center" data-toggle="collapse" data-target="#' . strtolower($region) . '">' . $region . '</a>';
        echo '<span class="d-flex align-items-center">';
        echo '<a href="#" class="icon-link ml-1" data-id="' . $regionId . '"><i class="fas fa-pencil-alt ml-1"></i></a>';
        echo '<a href="#" class="icon-link ml-1" data-id="' . $regionId . '"><i class="fas fa-plus ml-1"></i></a>';
        echo '<a href="#" class="icon-link ml-1" data-id="' . $regionId . '"><i class="fas fa-minus ml-1"></i></a>';
        echo '</span>';
        echo '</div>';
        

        echo '<ul id="' . strtolower($region) . '" class="collapse">';
        // Mettre à jour la région actuelle
        $currentRegion = $region;
    }

    // Afficher le département
    echo '<li class="nav-item">';
    echo '<div class="d-flex nav-link justify-content-between">';
    echo '<a class="nav-link d-flex justify-content-between dep-selection" href="#" id="'.$departementId.'">' . $departementNom . '</a>';
    echo '<span class="d-flex align-items-center">';
    echo '<a href="#" class="icon-link " data-id="' . $departementId . '"><i class="fas fa-pencil-alt ml-1 editIcon"></i></a>';
    echo '<a href="#" class="icon-link " data-id="' . $departementId . '"><i class="fas fa-plus ml-1 addIcon"></i></a>';
    echo '<a href="#" class="icon-link " data-id="' . $departementId . '"><i class="fas fa-minus ml-1 removeIcon"></i></a>';
    echo '</span>';
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
