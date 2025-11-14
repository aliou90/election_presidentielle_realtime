    /* -----------------------------------------------------
    AFFICHER LES LIEUX DE VOTE DE LA COMMUNE ET LEURS BUREAUX
    ---------------------------------------------------------*/
    $(document).ready(function() {
        $('.link').on('click', function(e) {
            e.preventDefault();
            var communeId = $(this).attr('id');
            var communeNom = $(this).closest('h5').find('b').text(); // Récupérer le nom de la commune
            $('#lieuxVoteModalLabel b').text(communeNom); // Mettre à jour le titre du modal avec le nom de la commune
            // Appel AJAX pour charger les lieux de vote
            $.ajax({
                url: 'get_lieux_vote.php',
                method: 'POST',
                data: { communeId: communeId },
                success: function(response) {
                    $('#lieuxVoteContent').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
    