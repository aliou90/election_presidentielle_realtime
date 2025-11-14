//<!--GRAPHIQUE DIAGRAMME EN DISQUE -->
        // Fonction pour récupérer les données des candidats depuis PHP
        async function getCandidatesData() {
            const response = await fetch('get_candidats.php');
            return await response.json();
        }

        // Fonction pour générer le graphique circulaire
        async function generatePieChart() {
            const candidates = await getCandidatesData();

            // Extraire les noms des candidats et les nombres de voix
            const labels = candidates.map(candidate => candidate.candidat);
            const votes = candidates.map(candidate => candidate.total_voix);

            // Créer le contexte du graphique
            const ctx = document.getElementById('pieChart').getContext('2d');

            // Créer le graphique circulaire
            const pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de Voix',
                        data: votes,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)'
                            // Ajoutez autant de couleurs que nécessaire
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                            // Ajoutez autant de couleurs que nécessaire
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    // Décalage du graphique pour un meilleur affichage
                    cutoutPercentage: 0,
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            });

            // Actualiser le graphique toutes les 5 secondes
            setInterval(async function() {
                const updatedData = await getCandidatesData();
                pieChart.data.labels = updatedData.map(candidate => candidate.candidat);
                pieChart.data.datasets[0].data = updatedData.map(candidate => candidate.total_voix);
                pieChart.update();
            }, 5000); // 5000 millisecondes = 5 secondes
        }

        // Appeler la fonction pour générer le graphique circulaire
        generatePieChart();

