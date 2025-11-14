<main id="comp_bureau_container" class="container" role="main">
<?php
// Menu de l'administrateur pour un département
// Appeler la fonction pour récupérer les bureaux
$bureau = get_bureau($connexion, $_SESSION['brid']);

// Vérifier si des données ont été récupérées
if ($bureau) {
    ?>
    <div class=" title d-flex align-items-center">
        <h1><?= $bureau[0]['region'] ?> |
         <?= $bureau[0]['departement'] ?> |
         <?= $bureau[0]['commune'] ?> |
         <?= $bureau[0]['lieu'] ?> |
         <?= $bureau[0]['bureau'] ?></h1>
    </div>
    <?php
}
?>

<?php
// Récupérer les résultats pour le bureau
$resultats = get_bureau_results($connexion, $_SESSION['brid']);

// Vérifier si le bureau est verrouillé
$bureau_verrouille = false;
if ($resultats && isset($resultats[0]['locked']) && $resultats[0]['locked'] == 1) {
    $bureau_verrouille = true;
}

// Si aucun résultat n'est récupéré, récupérer les noms des candidats depuis la base de données
if (!$resultats) {
    $candidats = get_candidats($connexion);
    // Initialiser les résultats avec le nombre de voix à zéro pour chaque candidat
    foreach ($candidats as $candidat) {
        $resultats[] = array(
            'cdid' => $candidat['id'],
            'candidat' => $candidat['candidat'],
            'voix' => NULL
        );
    }
}

// Afficher le formulaire
?>
<form id="form-resultats">
    <div id="alert-container"></div>
    <?php if ($bureau_verrouille) { ?>
        <div class="alert alert-info">
        <!-- Si le bureau est verrouillé, afficher un message -->
        <p>Les résultats pour ce bureau sont verrouillés. <br>Pour des besoins de modifications, veuillez Contactez les administrateurs.</p>
        </div>
        <!-- Bulletins blancs lecture seule -->
        <div class="form-group row">
            <label for="BulletinBlancs" class="col-sm-3 col-form-label bg-warning">Bulletins blancs</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="bblancs" value="<?= $bureau[0]['bblancs'] ?>" required readonly>
            </div>
        </div>
        <!-- Bulletins Nulls lecture seule  -->
        <div class="form-group row">
            <label for="BulletinNulls" class="col-sm-3 col-form-label bg-danger">Bulletins nulls</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="bnulls" value="<?= $bureau[0]['bnulls'] ?>" required readonly>
            </div>
        </div>
    <?php } else { ?>
         <!-- Bulletins blancs lecture/écriture-->
        <div class="form-group row">
            <label for="BulletinBlancs" class="col-sm-3 col-form-label bg-warning">Bulletins Blancs</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" id="bblancs" value="<?= $bureau[0]['bblancs'] ?>">
            </div>
        </div>
        <!-- Bulletins nulls lecture/écriture-->
        <div class="form-group row">
            <label for="BulletinNulls" class="col-sm-3 col-form-label bg-danger">Bulletins nulls</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" id="bnulls" value="<?= $bureau[0]['bnulls'] ?>">
            </div>
        </div>
    <?php } ?>

    <?php foreach ($resultats as $resultat) { ?>
        <div class="form-group row">
            <label for="voix_<?= $resultat['cdid'] ?>" class="col-sm-3 col-form-label bg-light"><?= $resultat['candidat'] ?></label>
            <div class="col-sm-9">
                <?php if ($bureau_verrouille) { ?>
                    <!-- Si le bureau est verrouillé, afficher le champ en lecture seule avec la valeur -->
                    <input type="text" class="form-control" id="voix_<?= $resultat['cdid'] ?>" value="<?= $resultat['voix'] ?>" required readonly>
                <?php } else { ?>
                    <!-- Sinon, afficher le champ de saisie avec des attributs data pour stocker l'ID du candidat -->
                    <input type="number" class="form-control CandidatVoix" id="voix_<?= $resultat['cdid'] ?>" data-candidat-id="<?= $resultat['cdid'] ?>" value="<?= $resultat['voix'] ?>">
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <?php if (!$bureau_verrouille) { ?>
        <!-- Si le bureau n'est pas verrouillé, ajouter un bouton d'enregistrement -->
        <button type="button" id="enregistrer-btn" class="btn btn-primary w-100 mb-5">Enregistrer</button>
    <?php } ?>
</form>

</main>
<script>
    // Fonction pour désactiver le bouton de basculement
    function disableToggleBtn() {
        var toggleBtn = document.querySelector('.toggle-btn');
        toggleBtn.classList.add('disabled');
        toggleBtn.disabled = true; // Ajouter cette ligne si vous voulez désactiver la fonctionnalité de clic
    }

    // Appeler la fonction pour désactiver le bouton de basculement au chargement du script
    disableToggleBtn();
</script>
