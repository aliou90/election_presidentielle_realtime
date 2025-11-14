<?php
// --------------- get_lieux_vote.php
// Inclure le fichier de connexion à la base de données et celui des requêtes
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';

if (isset($_POST['communeId'])) {
    // Récupérer l'ID de la commune à partir de la requête AJAX
    $communeId = $_POST['communeId'];

    // Requête pour récupérer les lieux de vote de la commune depuis la base de données
    $query_lieux = "SELECT * FROM lieux WHERE cid = :communeId";
    $stmt_lieux = $connexion->prepare($query_lieux);
    $stmt_lieux->bindParam(':communeId', $communeId);
    $stmt_lieux->execute();

    // Afficher les lieux et bureaux
    echo '<div id="tab-communes-modal">';

    // Vérifier s'il y a des résultats
    if ($stmt_lieux->rowCount() > 0) {
        // Parcourir chaque lieu de vote
        echo '<ul>';
        while ($row_lieux = $stmt_lieux->fetch(PDO::FETCH_ASSOC)) {
            echo '<li>' . $row_lieux['lieu'] . '</li>';

            // Requête pour récupérer les bureaux pour ce lieu de vote
            $query_bureaux = "SELECT * FROM bureaux WHERE lid = :lieuId";
            $stmt_bureaux = $connexion->prepare($query_bureaux);
            $stmt_bureaux->bindParam(':lieuId', $row_lieux['id']);
            $stmt_bureaux->execute();

            // Vérifier s'il y a des bureaux pour ce lieu de vote
            if ($stmt_bureaux->rowCount() > 0) {
                // Afficher un tableau pour chaque bureau
                while ($row_bureaux = $stmt_bureaux->fetch(PDO::FETCH_ASSOC)) {
                    // Requête pour récupérer les voix pour chaque candidat sur ce bureau
                    $query_voix = "SELECT candidats.id AS candidat_id, candidats.candidat AS candidat, candidats.abbr AS abbr, resultats.voix AS voix
                    FROM resultats 
                    JOIN candidats ON resultats.cdid = candidats.id 
                    WHERE resultats.brid = :bureauId";
                    $stmt_voix = $connexion->prepare($query_voix);
                    $stmt_voix->bindParam(':bureauId', $row_bureaux['id']);
                    $stmt_voix->execute();

                    echo '<div class="table-responsive">';
                    echo '<ul>';
                    echo '<li align="top">Bureau: ' . $row_bureaux['lid'] . ' - ' . $row_bureaux['id'] . '  ' ;
                    echo '(';
                    echo '<b>Votants: </b> ' . $row_bureaux['votants']  . ' - ';
                    echo '<b>Bulletins Blancs: </b> ' . $row_bureaux['bblancs']  . ' - ';
                    echo '<b>Bulletin Nulls: </b> ' . $row_bureaux['bnulls'];
                    echo ') </li>';
                    echo '</ul>';
                    echo '<table class="table table-bordered">';
                    echo '<thead class="bg-light">';
                    echo '<tr>';

                    // En-têtes des colonnes avec noms des candidats
                    while ($row_voix = $stmt_voix->fetch(PDO::FETCH_ASSOC)) {
                        echo '<th scope="col" id=" ' . $row_voix['candidat_id'] . ' ">' . $row_voix['candidat'] . '</th>';
                    }

                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    $stmt_voix->execute();

                    // Initialisez les variables pour trouver la voix maximale
                    $max_voix = 0;
                    $max_indices = [];
                    $index = 0;

                    // Trouvez la voix maximale dans les résultats
                    while ($row_voix = $stmt_voix->fetch(PDO::FETCH_ASSOC)) {
                        $voix = $row_voix['voix'];

                        // Si la voix est supérieure à max_voix, réinitialisez max_indices
                        if ($voix > $max_voix) {
                            $max_voix = $voix;
                            $max_indices = [$index];
                        }
                        // Si la voix est égale à max_voix, ajoutez l'index à max_indices
                        elseif ($voix == $max_voix) {
                            $max_indices[] = $index;
                        }

                        $index++;
                    }

                    // Réinitialisez le curseur pour parcourir à nouveau les résultats et générer les cellules
                    $stmt_voix->execute();
                    $index = 0;

                    // Affichez les voix pour chaque candidat sur ce bureau et mettez en surbrillance toutes les voix maximales
                    echo '<tr>';
                    while ($row_voix = $stmt_voix->fetch(PDO::FETCH_ASSOC)) {
                        // Si l'index actuel est dans max_indices, appliquez un style de fond vert
                        if (in_array($index, $max_indices)) {
                            echo '<td class="bg-success" id=" ' . $row_voix['candidat_id'] . ' ">' . $row_voix['voix'] . '</td>';
                        } else {
                            echo '<td id=" ' . $row_voix['candidat_id'] . ' ">' . $row_voix['voix'] . '</td>';
                        }
                        $index++;
                    }
                    echo '</tr>';

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                }
            } else {
                echo '<p>Aucun bureau trouvé pour ce lieu de vote.</p>';
            }
        }
        echo '</ul>';
    } else {
        echo '<p>Aucun lieu de vote trouvé pour cette commune.</p>';
    }
    echo '</div>';
} else {
    echo '<p>Paramètre communeId non trouvé.</p>';
}
?>
