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
} catch(PDOException $erreur) {
    // En cas d'erreur de connexion, afficher un message d'erreur
    echo "Erreur de connexion à la base de données : " . $erreur->getMessage();
}
?>
