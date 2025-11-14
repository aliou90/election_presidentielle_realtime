<?php
/** CALCUL ET MISE À JOUR DES RESULTATS DE VOTE SUR LES BUREAUX */ 

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

    // Récupérer tous les identifiants des bureaux
    $stmt_bureaux = $connexion->query("SELECT id FROM bureaux");
    $ids_bureaux = $stmt_bureaux->fetchAll(PDO::FETCH_COLUMN);

    // Générer aléatoirement le nombre de votes blancs et nuls
    $bblancs = rand(0, 4); // Nombre de votes blancs aléatoire
    $bnulls = rand(0, 4); // Nombre de votes nulls aléatoire

    echo "Calcul des résultats de votes." . PHP_EOL;
    // Parcourir tous les bureaux et mettre à jour les données
    foreach ($ids_bureaux as $brid) {
        // Calculer le nombre total de voix dans les résultats du bureau
        $stmt_total_voix = $connexion->prepare("SELECT SUM(voix) AS total_voix FROM resultats WHERE brid = :brid");
        $stmt_total_voix->bindParam(':brid', $brid);
        $stmt_total_voix->execute();
        $total_voix = $stmt_total_voix->fetchColumn();

        // Calculer le nombre total de votants
        $votants = $total_voix + $bblancs + $bnulls;

        // Préparer et exécuter la requête de mise à jour
        $stmt_update = $connexion->prepare("UPDATE bureaux SET votants = :votants, bblancs = :bblancs, bnulls = :bnulls WHERE id = :brid");
        $stmt_update->bindParam(':votants', $votants);
        $stmt_update->bindParam(':bblancs', $bblancs);
        $stmt_update->bindParam(':bnulls', $bnulls);
        $stmt_update->bindParam(':brid', $brid);
        $stmt_update->execute();
    }

    echo "Données de bureaux mises à jour avec succès." . PHP_EOL;
} catch(PDOException $erreur) {
    echo "Erreur lors de la mise à jour des données de bureaux : " . $erreur->getMessage();
}
?>
