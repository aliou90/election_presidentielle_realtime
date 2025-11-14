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

    
    // Insertion des régions
    $sql_insert_regions = "INSERT INTO regions (id, region) VALUES 
        (1, 'Dakar'), 
        (2, 'Diourbel'), 
        (3, 'Fatick'), 
        (4, 'Kaffrine'), 
        (5, 'Kaolack'), 
        (6, 'Kédougou'), 
        (7, 'Kolda'), 
        (8, 'Louga'), 
        (9, 'Matam'), 
        (10, 'Saint-Louis'), 
        (11, 'Sédhiou'), 
        (12, 'Tambacounda'), 
        (13, 'Thiès'), 
        (14, 'Ziguinchor')";
    $connexion->exec($sql_insert_regions);
    echo "Insertion des régions terminée avec succès.".PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de la création de la table 'regions' ou insertion des données : " . $erreur->getMessage();
}
?>
