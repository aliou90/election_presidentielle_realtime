<?php
// Informations de connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "presidentielles";

try {
    // Connexion à la base de données MySQL
    $connexion = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer la liste des lieux
    $stmt_lieux = $connexion->query("SELECT * FROM lieux");
    $lieux = $stmt_lieux->fetchAll(PDO::FETCH_ASSOC);

    // Préparation de la requête d'insertion des bureaux
    $insert_bureaux = $connexion->prepare("INSERT INTO bureaux (id, lid, electeurs) VALUES (:id, :lid, :electeurs)");

    $l = 1; // Initialisation de l'ID des bureaux
    // Boucle sur chaque lieu
    foreach ($lieux as $lieu) {
        $lid = $lieu['id'];
        $lname = $lieu['lieu'];
        // Générer un nombre aléatoire selon le min et max de bureaux
        $nombre_bureaux = 2; //rand(1, 3);
        for ($i = 1; $i <= $nombre_bureaux; $i++) {
            $bureau_id = $l;            // Définition de l'ID de l'actuel bureaux
            $electeurs = rand(50, 300);   // Insertion nombre d'electeurs aléatoire
            // Exécuter la requête d'insertion du bureau
            $insert_bureaux->execute(array(':id' => $bureau_id, ':lid' => $lid, ':electeurs' => $electeurs));
            
            echo "Bureau " . $bureau_id . " (Lieu de vote `" . $lname . "`): " . $electeurs . " électeur(s) insérés avec succès.".PHP_EOL;

            $l ++; // Indentation de l'ID des bureaux
        }
    }
    echo "Insertion des bureaux terminée avec succès." . PHP_EOL;
    
} catch (PDOException $e) {
    echo "Erreur lors de l'insertion des bureaux : " . $e->getMessage() . PHP_EOL;
}
?>
