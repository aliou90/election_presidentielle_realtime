/* -----------------------------------------------------
FILTRER LES RÉSULTATS SELON LE CANDIDAT SÉLECTIONNÉ
---------------------------------------------------------*/
// Sélectionnez l'élément <select> avec l'ID 'candidat-select'
var selectElement = document.getElementById('candidat-select');

// Ajoutez un événement de changement (change) à l'élément <select>
selectElement.addEventListener('change', function() {
    // Obtenez l'ID de l'option sélectionnée
    var selectedId = selectElement.value;

    // Sélectionnez l'élément div avec l'ID 'tab-communes'
    var tabCommunesDiv = document.getElementById('tab-communes');

    // Si le div 'tab-communes' est trouvé
    if (tabCommunesDiv) {
        // Sélectionnez tous les éléments <th> et <td> à l'intérieur de 'tab-communes'
        var elements = tabCommunesDiv.querySelectorAll('th, td');

        // Variable pour suivre si tous les éléments sont masqués
        var allHidden = true;

        // Parcourez tous les éléments <th> et <td> pour filtrer ceux à cacher
        elements.forEach(function(element) {
            // Obtenez l'ID de l'élément actuel
            var elementId = element.getAttribute('id');

            // Si l'ID de l'élément n'est pas égal à l'ID sélectionnée
            // et que l'élément a un ID (évitez d'appliquer aux éléments sans ID)
            if (elementId && elementId.trim() !== selectedId.trim()) {
                // Masquez l'élément
                element.style.display = 'none';
            } else {
                // Si l'élément a le même ID que l'option sélectionnée, laissez-le visible
                element.style.display = '';
                // Si au moins un élément est visible, tous ne sont pas masqués
                allHidden = false;
            }
        });

        // Si tous les éléments <th> et <td> sont masqués, réinitialisez la visibilité de tous les éléments
        if (allHidden) {
            elements.forEach(function(element) {
                // Affichez l'élément
                element.style.display = '';
            });
        }
    }
});