
//<!-- Graphique des Statistiques -->
// Fonction pour récupérer les données des candidats depuis PHP
function getCandidatesData() {
    return fetch('get_candidats.php')
        .then(response => response.json())
        .catch(error => console.error('Erreur lors de la récupération des données :', error));
}

// Fonction pour générer le diagramme à barres avec Chart.js
async function generateBarChart() {
    const candidates = await getCandidatesData();

    // Extraire les noms des candidats et les nombres de voix
    const labels = candidates.map(candidate => candidate.candidat);
    const votes = candidates.map(candidate => candidate.total_voix);

    // Obtenir l'objet Chart existant ou le créer s'il n'existe pas
    const ctx = document.getElementById('chart').getContext('2d');
    if(window.myChart) {
        window.myChart.data.labels = labels;
        window.myChart.data.datasets[0].data = votes;
        window.myChart.update();
    } else {
        window.myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre de Voix',
                    data: votes,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
}

// Appeler la fonction pour générer le diagramme à barres toutes les 5 secondes
setInterval(() => {
    generateBarChart();
}, 5000); // 5000 millisecondes = 5 secondes

// Appeler la fonction pour générer le diagramme dès que la page est chargée
window.addEventListener('load', () => {
    generateBarChart();
});