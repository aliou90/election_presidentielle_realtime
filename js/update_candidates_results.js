setInterval(function() {
    $.ajax({
        url: 'update_resultats.php',
        type: 'GET',
        success: function(data) {
            // Mettre à jour les données des candidats
            $('#candidatData').html(data);
        }
    });
    }, 5000); // Répéter toutes les 5 secondes
    