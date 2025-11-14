<?php
// Importation de fichier de traitent BACKEND
include_once(__DIR__. '/includes/conx.php');
include_once(__DIR__. '/includes/requests.php');
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

    <div class="container-fluid">
        <div class="row">
            <!-- Contenu principal -->
            <!-- Sidebar -->
            <?php include 'includes/sidebar.php' ?>

            <!-- Acceuil -->
            <main role="main" class="main-content col-md-9 ml-sm-auto col-lg-10 px-md-4" id="main">  
            <?php include 'includes/title.php' ?>
                <div class="container mt-1">  
                        <?php include 'includes/display_all_bureaux.php' ?>
                        <?php include 'includes/display_winners.php' ?>
                        <?php include 'includes/display_bar_diagram.php' ?>
                        <?php //include 'includes/display_disc_diagram.php' ?>
                        <?php include 'includes/display_all_candidates.php' ?>
                        <?php include 'includes/display_department_details.php' ?>
                </div>
            </main>
        </div>
    </div>
 
    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <!-- Scripts d'automatisation -->
    <!--Montrer/Cacher le menu gauche -->
    <script src="./js/sidebar_toogle.js"></script>
    <script src="./js/best_candidats.js"></script>
    <script src="./js/update_candidates_results.js"></script>
    <script src="./js/display_communes.js"></script>
    <script src="./js/barre_diagram.js"></script>
    <script src="./js/disc_diagram.js"></script>

    <script>
/*--------------------------------------------------------------
    GESTION DE L'AJOUT DE NOUVELLES DONNÉES
---------------------------------------------------------------*/
$(document).ready(function() {
    // ID des bureaux déjà présents dans le carrousel
    let existingBureaux = new Set();
    $('.carousel-item').each(function() {
        let captionText = $(this).find('.carousel-caption p').text(); // Récupérer le texte de la légende du carousel
        let bureauIdMatch = captionText.match(/Bureau (\d+)/); // Trouver l'ID du bureau
        if (bureauIdMatch) {
            existingBureaux.add(bureauIdMatch[1]); // Ajouter l'ID du bureau à l'ensemble des bureaux existants
        }
    });

    // Fonction pour vérifier s'il y a de nouveaux bureaux
    function checkForNewBureaux() {
        $.ajax({
            url: 'get_next_result.php', // URL de la requête AJAX
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Parcourir les résultats et vérifier s'il y a de nouveaux bureaux
                data.forEach(function(bureau) {
                    let bureauId = bureau.bureau_id; // Récupérer l'ID du bureau
                    if (!existingBureaux.has(bureauId)) {
                        // Si le bureau n'est pas déjà dans l'ensemble des bureaux existants, l'ajouter au carrousel
                        let activeClass = '';
                        if ($('.carousel-item').length === 0) {
                            activeClass = 'active'; // Si c'est la première diapositive, la définir comme active
                        }
                        
                        // Trouver le nombre de voix maximales dans ce bureau
                        let maxVoix = Math.max(...bureau.candidates.map(candidate => candidate.voix));
                        
                        // Créer une nouvelle diapositive pour le carrousel
                        let newSlide = `
                            <div class="carousel-item ${activeClass}">
                                <div class="carousel-caption d-none d-md-block position-relative" style="top: 0; left: 50%; transform: translateX(-50%);">
                                    <p style="font-weight: bold; color: black; text-align: center;">
                                        ${bureau.region} | ${bureau.departement} | ${bureau.commune} | ${bureau.lieu} | Bureau ${bureauId}
                                    </p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>${bureau.candidates.map(candidate => `<th>${candidate.name}</th>`).join('')}</tr>
                                        </thead>
                                        <tbody>
                                            <tr>${bureau.candidates.map(candidate => {
                                                let voixClass = candidate.voix === maxVoix ? 'class="bg-success"' : '';
                                                return `<td ${voixClass}>${candidate.voix}</td>`;
                                            }).join('')}</tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;

                        // Ajouter la nouvelle diapositive au carrousel
                        $('.carousel-inner').append(newSlide);
                        existingBureaux.add(bureauId); // Ajouter l'ID du bureau à l'ensemble des bureaux existants
                        saveCurrentCarouselIndex; // savegarder l'idex du slide affiché
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la récupération des nouveaux résultats:', error);
            }
        });
    }

    // Appeler la fonction toutes les 5 secondes pour vérifier les nouveaux bureaux
    setInterval(checkForNewBureaux, 5000);
});


/*--------------------------------------------------------------
    GESTION DU FOCUS DES SIDES APRÈS ACTUALISATION DE LA PAGE
---------------------------------------------------------------*/
    // Fonction pour sauvegarder l'index actuel de la diapositive visible dans le stockage local
    function saveCurrentCarouselIndex() {
        // Récupérer l'index de la diapositive active dans le carrousel
        const currentIndex = $('#resultsCarousel .carousel-item.active').index();
        // Sauvegarder l'index dans le stockage local
        localStorage.setItem('currentCarouselIndex', currentIndex);
    }

    // Fonction pour restaurer l'index du carrousel depuis le stockage local
    function restoreCarouselIndex() {
        // Récupérer l'index sauvegardé dans le stockage local
        const savedIndex = localStorage.getItem('currentCarouselIndex');
        if (savedIndex !== null) {
            // Convertir l'index en nombre et mettre à jour le carrousel pour se positionner sur la diapositive sauvegardée
            $('#resultsCarousel').carousel(parseInt(savedIndex, 10));
        }
    }

    // Lorsque la page est chargée, restaurer l'index du carrousel et configurer l'écouteur d'événement
    $(document).ready(() => {
        // Restaurer l'index du carrousel depuis le stockage local
        restoreCarouselIndex();
        // Configurer l'écouteur d'événement pour sauvegarder l'index actuel à chaque transition de diapositive
        $('#resultsCarousel').on('slid.bs.carousel', saveCurrentCarouselIndex);
    });
</script>



</body>
</html>

