<style>

</style>
<?php 
// Inclure le fichier de connexion à la base de données et celui des requêtes
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';


// Récupérer le département envoyé par Ajax
$departementId = $_POST['departement'];

try {
    // Préparer la requête SQL pour récupérer les communes du département, leurs nombre de lieux et leurs nombre de bureaux
    $query = "SELECT 
                    departements.departement AS dept_nom, 
                    communes.id AS commune_id, 
                    communes.commune, 
                    COUNT(DISTINCT lieux.id) AS lieux, 
                    COUNT(bureaux.id) AS bureaux, 
                    SUM(bureaux.electeurs) AS electeurs,
                    SUM(bureaux.votants) AS votants,
                    SUM(bureaux.bblancs) AS bblancs,
                    SUM(bureaux.bnulls) AS bnulls
                FROM 
                    departements
                INNER JOIN 
                    communes ON departements.id = communes.did
                LEFT JOIN 
                    lieux ON communes.id = lieux.cid
                LEFT JOIN 
                    bureaux ON lieux.id = bureaux.lid
                WHERE 
                    departements.id = :departement
                GROUP BY 
                    departements.id, communes.id
                ORDER BY 
                    departements.departement, communes.commune;";
    
    $stmt = $connexion->prepare($query);
    $stmt->bindParam(':departement', $departementId);
    $stmt->execute();

    // Vérifier s'il y a des résultats
    if ($stmt->rowCount() > 0) {
        // Si des communes sont trouvées, générer le HTML correspondant
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Créez une variable pour stocker le code HTML
        $html = '<div class="d-flex justify-content-between align-items-center">'; // Utilisation des classes Bootstrap pour l'alignement

        // Ajoutez le titre du département
        $html .= '<h2>Département de ' . htmlspecialchars($row['dept_nom']) . '</h2>';

        // Préparez la requête SQL pour obtenir les candidats
        $query_candidats = $connexion->prepare("SELECT candidats.id AS candidat_id,
                                                candidats.candidat AS candidat,
                                                candidats.abbr AS abbr
                                        FROM candidats");

        // Exécutez la requête
        $query_candidats->execute();

        // Commencez le formulaire avec une liste déroulante et une balise `div` pour l'alignement
        $html .= '<form class="form-group mb-0">';

        // Créez un `div` avec `d-flex` et `align-items-center` pour aligner `Filtrer` et `<select>`
        $html .= '<div class="d-flex align-items-center">';

        // Ajoutez l'étiquette "Filtrer" et la liste déroulante
        $html .= '<label for="candidat-select" class="mr-2"><strong>Filtrer</strong></label>';
        $html .= '<select id="candidat-select" class="form-control">';

        // Option pour réafficher tous les candidats
        $html .= '<optgroup>';
        $html .= '<option value="0">Tous</option>';
        $html .= '</optgroup>';
        $html .= '<optgroup>';
        // Parcourez les résultats et ajoutez les candidats à la liste déroulante
        while ($row_candidats = $query_candidats->fetch(PDO::FETCH_ASSOC)) {
            $candidat_id = htmlspecialchars($row_candidats['candidat_id']);
            $candidat = htmlspecialchars($row_candidats['candidat']);
            $abbr = htmlspecialchars($row_candidats['abbr']);
            
            // Ajoutez chaque candidat comme option dans la liste déroulante
            $html .= '<option value="' . $candidat_id . '">' . $candidat . ' (' . $abbr . ')</option>';
        }
        $html .= '</optgroup>';

        // Fermez la liste déroulante et le div
        $html .= '</select>';
        $html .= '</div>'; // Fermez le div contenant `Filtrer` et `select`

        // Fermez le formulaire
        $html .= '</form>';

        // Fermez la balise div
        $html .= '</div>';

        // Boucle sur les communes
        $html .= '<div id="tab-communes">';
        do {
            $html .= '<div class="container">';
            $html .= '<h5>'; 
            $html .= 'Commune de <b>' . $row['commune'] . '</b>';
            $html .= '<small> (Inscrits: ' . $row['electeurs'] . ' | </small>';
            $html .= '<small><a href="#" id="' . $row['commune_id'] . '" class="link" data-toggle="modal" data-target="#lieuxVoteModal" >Lieux de vote</a>: ' . $row['lieux'] . ' | </small>';
            $html .= '<small><a href="#" id="' . $row['commune_id'] . '" class="link" data-toggle="modal" data-target="#lieuxVoteModal" >Bureaux</a> : ' . $row['bureaux'] . ') </small>';
            $html .= ' </h5>';
            $html .= '(Votants: ' . $row['votants'] . ' | Bulletin Blancs: ' . $row['bblancs'] . ' | Bulletin Nulls : ' . $row['bnulls'] . ')';

            // Récupérer total les voix pour chaque candidat dans toutes les bureaux des lieux de la commune
            $query_voix = "SELECT candidats.id AS candidat_id,
                                candidats.candidat AS candidat,
                                candidats.abbr AS abbr,
                                SUM(resultats.voix) AS voix
                        FROM candidats
                        INNER JOIN resultats ON candidats.id = resultats.cdid
                        INNER JOIN bureaux ON resultats.brid = bureaux.id
                        INNER JOIN lieux ON bureaux.lid = lieux.id
                        INNER JOIN communes ON lieux.cid = communes.id
                        WHERE communes.id = :communeId
                        GROUP BY candidats.id, candidats.candidat;";
            
            $stmt_voix = $connexion->prepare($query_voix);
            $stmt_voix->bindParam(':communeId', $row['commune_id']);
            $stmt_voix->execute();

            // Tableau Bootstrap
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered">';
            $html .= '<thead class="bg-light">';
            $html .= '<tr>';

            // Construire l'en-tête du tableau avec les noms candidats
            $abbrs = [];
            while ($row_voix = $stmt_voix->fetch(PDO::FETCH_ASSOC)) {
                $abbrs[] = $row_voix['abbr'];
                $html .= '<th scope="col" id=" ' . $row_voix['candidat_id'] . ' ">' . $row_voix['candidat'] . '</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            // Réinitialiser le curseur pour récupérer à nouveau les résultats
            $stmt_voix->execute();

            // Construire les lignes du tableau avec le total de voix pour chaque candidat
            $html .= '<tr>';
            
            // Initialisez la variable max_voix et la liste d'indices de colonnes avec la voix maximale
            $max_voix = 0;
            $max_indices = [];

            $index = 0;
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

            // Réinitialisez le curseur pour parcourir à nouveau les résultats et générer les cellules de la ligne
            $stmt_voix->execute();

            $index = 0;
            while ($row_voix = $stmt_voix->fetch(PDO::FETCH_ASSOC)) {
                $voix = $row_voix['voix'];
                
                // Appliquez un style de fond vert à toutes les cellules contenant une voix maximale
                if (in_array($index, $max_indices)) {
                    $html .= '<td id=" ' . $row_voix['candidat_id'] . ' " class="bg-success">' . $voix . '</td>';
                } else {
                    $html .= '<td id=" ' . $row_voix['candidat_id'] . ' ">' . $voix . '</td>';
                }

                $index++;
            }

            $html .= '</tr>';

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';

            $html .= '</div>';
        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
        $html .= '</div>';

        echo $html;
    } else {
        // Si aucun résultat n'est trouvé, renvoyer un message d'erreur
        echo 'Aucune commune trouvée pour ce département.';
    }
} catch (PDOException $e) {
    // En cas d'erreur de la base de données, afficher l'erreur
    echo 'Erreur de la base de données : '.$e->getMessage();
}
?>

<!-- Modal pour afficher les lieux de vote -->
<div class="modal fade" id="lieuxVoteModal" tabindex="-1" role="dialog" aria-labelledby="lieuxVoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-full" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title" id="lieuxVoteModalLabel">Lieux de vote de la commune: <b></b></h5>

          <!-- Liste déroulante pour filtrer les candidats -->
          <form class="form-group mb-0 d-flex align-items-center">
            <label for="candidat-modal-select" class="mr-2"><strong>Filtrer</strong></label>
            <select id="candidat-modal-select" class="form-control">
              <optgroup>
              <option value="0">Tous</option>
              </optgroup>
              <optgroup>
              <?php
              // Exécutez la requête
              $query_candidats->execute();
              // Parcourez les résultats et ajoutez les candidats à la liste déroulante
              while ($row_candidats = $query_candidats->fetch(PDO::FETCH_ASSOC)) {
                  $candidat_id = htmlspecialchars($row_candidats['candidat_id']);
                  $candidat = htmlspecialchars($row_candidats['candidat']);
                  $abbr = htmlspecialchars($row_candidats['abbr']);
                  
                  // Ajoutez chaque candidat comme option dans la liste déroulante
                  echo '<option value="' . $candidat_id . '">' . $candidat . ' (' . $abbr . ')</option>';
              }
              ?>
              </optgroup>
            </select>
          </form>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="lieuxVoteContent">
        <!-- Contenu des lieux de vote sera chargé ici via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- LES SCRIPT DE L'AFFICHAGES DES BUREAUX DANS LES COMMUNES ET LIEUX -->
<script src="./js/display_lieux.js"></script>
<script src="./js/filter_cadidat_from_communes.js"></script>
<script src="./js/filter_candidat_from_lieux.js"></script>
