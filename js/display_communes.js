//<!-- AFFICHER LES COMMUNES DU DÉPATMENT SÉLECTIONNÉ -->
$(document).ready(function(){
    $('.dep-selection').click(function(e){
        e.preventDefault();
        var target = $(this).attr('id'); // Récupérer l'ID du département
        if(target){
            $.ajax({
                url: 'get_commune_data.php',
                type: 'POST',
                data: {departement: target},
                success: function(response){
                    $('#communeData').html(response);
                    $('#candidatData').hide();
                    $('#chart').hide();
                    $('#pieChart').hide();
                },
                error: function(xhr, status, error){
                    console.error(xhr.responseText);
                }
            });
        }
    });
});

