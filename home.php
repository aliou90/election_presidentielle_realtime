<?php
// Importation de fichier de traitent BACKEND
include_once(__DIR__.'/includes/conx.php');
include_once(__DIR__.'/includes/requests.php');

// Vérifie si une session est active
if (!checkSession()) {
    // Rediriger vers login.php
    header('Location: login.php');
} 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Élection Présidentielle - Résultats et Statistiques</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    

    <!-- Composants HTML -->
    <?php 
        // Importer le composant approprié
        if (isset($_SESSION['brid']) && $_SESSION['brid'] && $_SESSION['role'] === 'bureau') {
          include __DIR__.'/includes/comp_bureau.php';
        } elseif (isset($_SESSION['rid']) && $_SESSION['rid'] && $_SESSION['role'] === 'cer') {
          include __DIR__.'/includes/comp_cer.php';
        } elseif (isset($_SESSION['did']) && $_SESSION['did'] && $_SESSION['role'] === 'ced') {
          include __DIR__.'/includes/comp_ced.php';
        } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'cena') {
          include __DIR__.'/includes/comp_cena.php';
        } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
          include __DIR__.'/includes/comp_admin.php';
        } else {
          include __DIR__.'/includes/404.php';
        }
        
    ?>
    



    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <!-- Scripts d'automatisation -->
    <!--Montrer/Cacher le menu gauche -->
    <script src="./js/sidebar_toogle.js"></script>

<script>
  function refreshSidebar() {
    $('#regionsList').load('includes/sidebar_admin.php');
}

// Appel de la fonction pour la première fois
refreshSidebar();

</script>

<?php
// Préparez les données PHP pour être utilisées dans JavaScript
$js_resultats = json_encode($resultats);
?>

<script>
$(document).ready(function() {
    // Charger les données PHP dans JavaScript
    var resultats = <?php echo $js_resultats; ?>;

    // Gérer le clic sur le bouton d'enregistrement
    $('#enregistrer-btn').click(function() {
        // Créer un tableau pour stocker les données
        var data = {
            bureauId: <?= $_SESSION['brid'] ?>, // Récupérer l'ID du bureau depuis la session PHP
            resultats: {},
            bblancs: null,
            bnulls: null
        };

        // Récupérer les données de chaque champ de saisie
        $('.CandidatVoix').each(function() {
            var candidatId = $(this).data('candidat-id');
            var voix = $(this).val();
            data.resultats[candidatId] = voix;
        });

        // Récupérer les bulletins blancs et nulls et ajout dans le stockage
        const bblancs = $("#bblancs").val();
        data.bblancs= bblancs;
        const bnulls = $("#bnulls").val();
        data.bnulls= bnulls;

        // Afficher la boîte de dialogue de confirmation avec les résultats
        afficherConfirmation(data, resultats, bblancs, bnulls);
    });

    // Gérer la confirmation de l'enregistrement
    $('#confirm-btn').click(function() {
        // Récupérer les données de la boîte de dialogue
        var data = $('#confirmation-modal').data('data');

        // Envoyer les données au script PHP via Ajax
        $.ajax({
            url: 'enregistrer_resultats.php',
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                // Afficher la réponse du serveur
                console.log(response);
                if (response.success) {
                    // Stocker le message d'alerte de succès dans le stockage local
                    localStorage.setItem('successMessage', response.message);
                    // Recharger la page après le succès
                    location.reload();
                } else {
                    // Afficher un message d'alerte en cas d'échec
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Gérer les erreurs
                console.error(error);
                // Afficher un message d'alerte en cas d'erreur
                alert("Une erreur s'est produite lors de la requête Ajax.");
            }

        });

        // Cacher la boîte de dialogue de confirmation
        $('#confirmation-modal').modal('hide');
    });

    // Gérer l'annulation de l'enregistrement
    $('#cancel-btn').click(function() {
        // Cacher la boîte de dialogue de confirmation
        $('#confirmation-modal').modal('hide');
    });

    // Vérifier s'il y a un message d'alerte de succès stocké dans le stockage local
    var successMessage = localStorage.getItem('successMessage');
    if (successMessage) {
        // Afficher le message d'alerte de succès
        alert(successMessage);
        // Effacer le message d'alerte de succès du stockage local
        localStorage.removeItem('successMessage');
    }
});

function afficherConfirmation(data, resultats, bblancs, bnulls) {
    // Afficher la boîte de dialogue de confirmation
    $('#confirmation-modal').modal('show');
    // Stocker les données dans la boîte de dialogue
    $('#confirmation-modal').data('data', data);

    // Générer le tableau des résultats
    var tableauResultats = '';
    $.each(data.resultats, function(candidatId, voix) {
        var nomCandidat = '';
        $.each(resultats, function(index, resultat) {
            if (resultat.cdid == candidatId) {
                nomCandidat = resultat.candidat;
                return false; // Sortir de la boucle une fois que le candidat est trouvé
            }
        });
        tableauResultats += '<tr><td class="bg-light">' + nomCandidat + '</td><td>' + voix + '</td></tr>';
    });

    // Ajouter les bulletins blancs et nulls dans le tableau
    tableauResultats += '<tr><td class="bg-warning">Nombre de bulletins blancs</td><td>' + bblancs + '</td></tr>';
    tableauResultats += '<tr><td class="bg-danger">Nombre de bulletins nulls</td><td>' + bnulls + '</td></tr>';   

    // Afficher le tableau des résultats dans la boîte de dialogue
    $('#confirmation-table').html(tableauResultats);
}
</script>


<!-- Boîte de dialogue de confirmation -->
<div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmation-modal-label">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir enregistrer ces résultats ? Cette opération ne peut être annulée.
            </div>
            <!-- Tableau responsive des résultats -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Candidat</th>
                            <th scope="col">Nombre de voix</th>
                        </tr>
                    </thead>
                    <tbody id="confirmation-table">
                        <!-- Les données du tableau seront générées ici -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancel-btn" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirm-btn">Confirmer</button>
            </div>
        </div>
    </div>
</div>



</body>
</html>
