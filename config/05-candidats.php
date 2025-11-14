<?php
// Informations de connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "presidentielles";

try {
    // Connexion à la base de données MySQL avec PDO
    $connexion = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    // Configuration des options pour afficher les erreurs PDO
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Ajou des candidats." . PHP_EOL;
    // Préparation de la requête d'insertion pour les candidats
    $insert_candidats = "INSERT INTO candidats (candidat, parti, abbr, coalition, img) VALUES 
                        ('Amadou Ba', 'Alliance pour la République', 'AB', 'Benno Bokk Yaakaar', 'AB.JPG'),
                        ('Bassirou Diomaye Faye', 'Patriotes africains du Sénégal pour le travail, l\'éthique et la fraternité', 'BDF', 'Yewwi Askan Wi', 'BDF.JPG'),
                        ('Khalifa Sall', 'Taxawu Senegaal', 'KS', 'Yewwi Askan Wi', 'KS.JPG'),
                        ('Idrissa Seck', 'Rewmi', 'IS', 'Indépendant', 'IS.JPG'),
                        ('Anta Babacar Ngom', 'Alternative pour la Relève Citoyenne', 'ABN', 'Indépendant', 'ABN.JPG'),
                        ('Mahammed Abdallah Dionne', 'Convergence des Cadres Républicains', 'MAD', 'Benno Bokk Yaakaar', 'MAD.JPG'),
                        ('Aly Ngouille Ndiaye', 'Alliance pour la République', 'ANN', 'Benno Bokk Yaakaar', 'ANN.JPG'),
                        ('Aliou Mamadou Dia', 'Mouvement pour l\'Emergence et le Développement Durable', 'AMMD', 'Indépendant', 'AMMD.JPG'),
                        ('Boubacar Camara', 'Parti Socialiste', 'BC', 'Benno Bokk Yaakaar', 'BC.JPG'),
                        ('Mamadou Lamine Diallo', 'Mouvement Tekki', 'MLD', 'Indépendant', 'MLD.JPG'),
                        ('Papa Djibril Fall', 'Les Serviteurs', 'PDF', 'Indépendant', 'PDF.JPG'),
                        ('Thierno Alassane Sall', 'République des Valeurs', 'TAS', 'Indépendant', 'TAS.JPG'),
                        ('Malick Gackou', 'Grand Parti', 'MG', 'Indépendant', 'MG.JPG'),
                        ('Mamadou Diao', 'Alternative Démocratique pour la République', 'MD', 'Indépendant', 'MD.JPG'),
                        ('Serigne Mboup', 'Candidat indépendant', 'SM', 'Indépendant', 'SM.JPG'),
                        ('Déthié Fall', 'Parti Républicain pour le Progrès', 'DF', 'Yewwi Askan Wi', 'DF.JPG'),
                        ('Daouda Ndiaye', 'Candidat indépendant', 'DN', 'Indépendant', 'DN.JPG')";
                        
    // Exécution de la requête d'insertion
    $connexion->exec($insert_candidats);
    
    echo "Données des candidats insérées avec succès." . PHP_EOL;

} catch(PDOException $e) {
    // En cas d'erreur lors de l'insertion, afficher l'erreur
    echo "Erreur lors de l'insertion des données des candidats : " . $e->getMessage() . PHP_EOL;
}
