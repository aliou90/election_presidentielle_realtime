<!-- Liste complète des candidats-->
<div class="row" id="candidatData">
    <?php
        // Récupérer les informations des candidats depuis la base de données
        $candidats = get_candidats($connexion);

        // Parcourir les candidats et afficher chaque carte
        foreach ($candidats as $candidat) {
            echo '<div class="col-md-2">';
            echo '<div class="card" style="height: 200px;">';
            echo '<img src="img/' . $candidat['abbr'] . '.jpg" class="card-img-top" alt="' . $candidat['candidat'] . '" style="max-width: 200px; max-height: 50px;">';
            echo '<div class="card-body">';
            echo '<h6 class="card-title" style="height: 50px;">' . $candidat['candidat'] . '</h6>';
            echo '<p class="card-text small candidatID" id="' . $candidat['id'] . '">'; 
            echo 'Voix : ' . $candidat['total_voix'] . ' / ' . $candidat['pourcentage'] . '%</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    ?>
</div>