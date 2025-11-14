<?php
// Informations de connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "presidentielles";

try {
    // Connexion à la base de données MySQL
    $connexion = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    
    // Configuration des options pour afficher les erreurs PDO
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Calcul du nombre de candidats depuis la table "candidats"
    $stmt_candidats = $connexion->query("SELECT COUNT(*) AS total_candidats FROM candidats");
    $result_candidats = $stmt_candidats->fetch(PDO::FETCH_ASSOC);
    $nombre_candidats = $result_candidats['total_candidats'];

    // Calcul du nombre de bureaux depuis la table "bureaux"
    $stmt_bureaux = $connexion->query("SELECT COUNT(*) AS total_bureaux FROM bureaux");
    $result_bureaux = $stmt_bureaux->fetch(PDO::FETCH_ASSOC);
    $nombre_bureaux = $result_bureaux['total_bureaux'];
    
// Insertion des données de résultats pour les 169 communes et les 17 candidats
$sql_insert = "INSERT INTO resultats (brid, cdid, voix) VALUES ";

echo "Insertion des resultats de vote aléatoires." . PHP_EOL;
for ($i = 1; $i <= $nombre_bureaux; $i++) {
    // Ajout d'une pause de 5 secondes après chaque bureau
    sleep(5);
    
    // Données pour les 17 candidats (cdid de 1 à 17)
    for ($j = 1; $j <= $nombre_candidats; $j++) {
        $voix = 0; // Initialisation du nombre de voix
        
        // Pour le candidat 2, plus de 60% des voix
        if ($j == 2) {
            $voix = rand(6000, 8000); // Voix entre 6000 et 8000
        } 
        // Pour le candidat 1, entre 20 et 30% des voix
        elseif ($j == 1) {
            $voix = rand(500, 3000); // Voix entre 500 et 3000
        }
        // Pour les autres candidats 
        else {
            $voix = rand(10, 500); // Voix entre 10 et 500
        }
        
        // Insertion des données dans la requête SQL
        $sql_insert .= "($i, $j, $voix)";
        
        // Ajout d'une virgule et un espace sauf pour le dernier enregistrement
        if ($j != $nombre_candidats) {
            $sql_insert .= ", ";
        } else {
            // Ajout du point-virgule pour terminer la requête SQL
            $sql_insert .= ";";
        }
    }
    // Afficher la requête d'insertion pour déboguer
    echo $sql_insert . PHP_EOL;
    
    // Exécution de la requête SQL pour chaque bureau
    $connexion->exec($sql_insert);
    
    // Réinitialisation de la requête d'insertion pour le prochain bureau
    $sql_insert = "INSERT INTO resultats (brid, cdid, voix) VALUES ";
}

echo "Données de résultats insérées avec succès." . PHP_EOL;

    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des données de résultats : " . $erreur->getMessage();
}

// Mettre à jour les resultats des bureaux (votants, bblancs, bnulls) dans la table
include('08-votes.php'); 
?>
