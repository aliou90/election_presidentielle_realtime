<?php 
// Requête SQL pour récupérer les résultats pour chaque bureau
$query = "SELECT candidats.candidat, resultats.voix, resultats.date AS date, regions.region AS region,
                 departements.departement AS departement, communes.commune AS commune,
                 lieux.lieu AS lieu, bureaux.id AS bureau_id
          FROM candidats
          JOIN resultats ON candidats.id = resultats.cdid
          JOIN bureaux ON resultats.brid = bureaux.id
          JOIN lieux ON bureaux.lid = lieux.id
          JOIN communes ON lieux.cid = communes.id
          JOIN departements ON communes.did = departements.id
          JOIN regions ON departements.rid = regions.id
          -- ORDER BY region, departement, commune, lieu, bureau_id, date  #Pour ordoner les Rg/Dep etc... --
          ORDER BY bureau_id, date -- Pour ordonner selon la date d'insertion des données --
          ";

$stmt = $connexion->query($query);

// Tableau pour conserver les résultats regroupés par bureau
$bureau_results = [];

// Parcourir les résultats de la requête et les organiser par bureau
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $bureau_id = $row['bureau_id'];
    $region = $row['region'];
    $departement = $row['departement'];
    $commune = $row['commune'];
    $lieu = $row['lieu'];
    $candidat = $row['candidat'];
    $voix = $row['voix'];

    // Organiser les résultats par bureau
    if (!isset($bureau_results[$bureau_id])) {
        $bureau_results[$bureau_id] = [
            'region' => $region,
            'departement' => $departement,
            'commune' => $commune,
            'lieu' => $lieu,
            'candidates' => [],
        ];
    }
    
    $bureau_results[$bureau_id]['candidates'][$candidat] = $voix;
}
echo '<details open>';
echo '<summary>Résultats en temps réel</summary>';
// Afficher le carrousel Bootstrap avec les résultats par bureau
echo '<div id="resultsCarousel" class="carousel slide" data-ride="carousel" data-interval="5000" data-wrap="false" style="width: 100%;">';

// Ajoutez les contrôles de navigation pour aller à la diapositive précédente ou suivante
echo '<a class="carousel-control-prev" href="#resultsCarousel" role="button" data-slide="prev">';
echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
echo '<span class="sr-only">Précédent</span>';
echo '</a>';
echo '<a class="carousel-control-next" href="#resultsCarousel" role="button" data-slide="next">';
echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
echo '<span class="sr-only">Suivant</span>';
echo '</a>';

echo '<div class="carousel-inner">';

$first = true;
foreach ($bureau_results as $bureau_id => $bureau_data) {
    $region = $bureau_data['region'];
    $departement = $bureau_data['departement'];
    $commune = $bureau_data['commune'];
    $lieu = $bureau_data['lieu'];
    $candidates = $bureau_data['candidates'];

    // Trouver les voix maximales
    $max_voix = max($candidates);

    // Définir la première diapositive comme active
    $active_class = $first ? 'active' : '';
    $first = false;

    // Afficher la diapositive du carrousel
    echo '<div class="carousel-item ' . $active_class . '">';
    // Afficher la légende du carrousel (caption)
    echo '<div class="carousel-caption d-none d-md-block position-relative" style="top: 0; left: 50%; transform: translateX(-50%);">';
    echo '<p style="font-weight: bold; color: black; text-align: center;">';
    echo $region . ' | ' . $departement . ' | ' . $commune . ' | ' . $lieu . ' | Bureau ' . $bureau_id;
    echo '</p>';
    echo '</div>';

    // Afficher le tableau
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered">';
    echo '<thead class="bg-light">';
    echo '<tr>';

    // Afficher les noms des candidats en en-tête
    foreach ($candidates as $candidat => $voix) {
        echo '<th>' . $candidat . '</th>';
    }

    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr>';

    // Afficher les voix de chaque candidat, en mettant en surbrillance les voix maximales
    foreach ($candidates as $candidat => $voix) {
        if ($voix == $max_voix) {
            echo '<td class="bg-success">' . $voix . '</td>';
        } else {
            echo '<td>' . $voix . '</td>';
        }
    }

    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Fermer la div.table-responsive
    echo '</div>'; // Fermer la div.carousel-item
}

echo '</div>'; // Fermer la div.carousel-inner
echo '</div>'; // Fermer la div#resultsCarousel


echo '</details>';

?>
