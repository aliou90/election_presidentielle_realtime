//<!-- MISE À JOUR DES @ MEILLEURS CANDIDATS -->
$(document).ready(function() {
    // Fonction pour charger les meilleurs candidats via AJAX
    function loadBestCandidates() {
        $.ajax({
            url: 'get_best_candidates.php', // Assurez-vous d'ajuster le nom du fichier selon votre configuration
            type: 'GET',
            success: function(response) {
                var candidates = JSON.parse(response);

                function isValidNumber(value) {
                    return !isNaN(value) && value !== null && value !== '';
                }
                
                // Mettre à jour les données du premier candidat
                $('#c1img img').attr('src', 'img/' + candidates[0]['abbr'] + '.jpg');
                $('#c1pourcentage').text(isValidNumber(candidates[0]['pourcentage']) ? candidates[0]['pourcentage'] + '%' : '');
                $('#c1ttvoix').text(isValidNumber(candidates[0]['total_voix']) ? 'Voix : ' + candidates[0]['total_voix'] : 'En attente des résultats');
                $('#c1candidat').text(candidates[0]['candidat']);
                
                // Mettre à jour les données du deuxième candidat
                $('#c2img img').attr('src', 'img/' + candidates[1]['abbr'] + '.jpg');
                $('#c2pourcentage').text(isValidNumber(candidates[1]['pourcentage']) ? candidates[1]['pourcentage'] + '%' : '');
                $('#c2voix').text(isValidNumber(candidates[1]['total_voix']) ? 'Voix : ' + candidates[1]['total_voix'] : 'En attente des résultats');
                $('#c2candidat').text(candidates[1]['candidat']);
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Charger les meilleurs candidats au chargement de la page
    loadBestCandidates();

    // Actualiser les meilleurs candidats toutes les 5 secondes
    setInterval(function() {
        loadBestCandidates();
    }, 5000); // 5000 millisecondes = 5 secondes
});
