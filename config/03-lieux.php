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

    // Récupérer la liste des communes
    $stmt_communes = $connexion->query("SELECT * FROM communes");
    $communes = $stmt_communes->fetchAll(PDO::FETCH_ASSOC);

    // Préparation de la requête d'insertion des lieux
    $insert_lieux = $connexion->prepare("INSERT INTO lieux (id, cid, lieu) VALUES (:id, :cid, :lieu)");

    $lid = 1; // Initialisation de l'ID des lieux
    $lb = 1;  // Deuxième chiffre identifiant nom lieu
    // Boucle sur chaque commune
    foreach ($communes as $commune) {
        $cid = $commune['id'];
        $cname = $commune['commune'];
        // Générer un nombre aléatoire selon le min et max de lieux
        $nombre_lieux = rand(1, 20);
        for ($i = 1; $i <= $nombre_lieux; $i++) {
            $lieu_id = $lid;                          // Définition de l'ID de l'actuel lieux
            $nomLieux = "Lieu " . $lb . '-' . $i;    // Définition du lieu de l'actuel lieux
            // Exécuter la requête d'insertion du lieu
            $insert_lieux->execute(array(':id' => $lieu_id, ':cid' => $cid, ':lieu' => $nomLieux));
            
            echo "(" . $lieu_id . ") | Commune '" . $cid . ". " . $cname . "': lieu de vote '" . $nomLieux . "' inséré avec succès." . PHP_EOL;

            $lid ++; // Indentation de l'ID des lieux
        }
        $lb ++; // Indentation Deuxième chiffre identifiant nom lieu
    }
    echo "Insertion des lieux terminée avec succès." . PHP_EOL;

} catch (PDOException $e) {
    echo "Erreur lors de l'insertion des lieux : " . $e->getMessage() . PHP_EOL;
}
?>
