// Filtrer les résultats selon le candidat sélectionné dans le modal
// Filtrer les résultats selon le candidat sélectionné dans le modal
document.getElementById('candidat-modal-select').addEventListener('change', function() {
    // Obtenir la valeur sélectionnée dans la liste déroulante
    var selectedId = this.value;
    // Obtenir le div contenant la table avec l'ID 'tab-communes-modal'
    var tabCommunesModalDiv = document.getElementById('tab-communes-modal');
    
    // Vérifier si le div existe
    if (tabCommunesModalDiv) {
        // Sélectionner tous les éléments <th> et <td> à l'intérieur de 'tab-communes-modal'
        var elements = tabCommunesModalDiv.querySelectorAll('th, td');
        
        // Si l'option sélectionnée est '0' (Tous), réinitialiser la visibilité de tous les éléments
        if (selectedId === "0") {
            elements.forEach(function(element) {
                element.style.display = ''; // Rendre l'élément visible
            });
        } else {
            // Sinon, réinitialisez la visibilité de tous les éléments
            elements.forEach(function(element) {
                element.style.display = '';
            });

            // Parcourez tous les éléments <th> et <td> pour filtrer ceux à cacher
            elements.forEach(function(element) {
                var elementId = element.getAttribute('id');

                // Masquer l'élément si son ID ne correspond pas à l'ID sélectionnée
                if (elementId && elementId.trim() !== selectedId.trim()) {
                    element.style.display = 'none';
                }
            });
        }
    }
});
