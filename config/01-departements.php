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
    
    // Départements à insérer avec leurs régions correspondantes
    $departements = array(
        1 => array('Dakar', 'Guédiawaye', 'Pikine', 'Rufisque'),
        2 => array('Diourbel', 'Bambey', 'Mbacké'),
        3 => array('Fatick', 'Foundiougne', 'Gossas'),
        4 => array( 'Kaffrine', 'Birkilane', 'Koungheul'),
        5 => array('Kaolack', 'Guinguinéo', 'Nioro du Rip'),
        6 => array('Kédougou', 'Salemata', 'Saraya'),
        7 => array('Kolda', 'Médina Yoro Foulah', 'Vélingara'),
        8 => array( 'Louga', 'Kébémer', 'Linguère'),
        9 => array('Matam', 'Kanel', 'Ranérou Ferlo'),
        10 => array('Saint-Louis', 'Dagana', 'Podor'),
        11 => array( 'Sédhiou', 'Bounkiling', 'Goudomp'),
        12 => array('Tambacounda', 'Bakel', 'Goudiry', 'Koumpentoum'),
        13 => array('Thiès', "M'bour", 'Tivaouane'),
        14 => array('Ziguinchor', 'Bignona', 'Oussouye')
    );
    
    // Préparer la requête d'insertion
    $requete = $connexion->prepare("INSERT INTO departements (id, rid, departement) VALUES (:did, :rid, :departement)");
    
    $did = 1; //Initialisation de 'ID' du département
    // Parcourir le tableau des départements et insérer chaque département dans la table
    foreach ($departements as $rid => $liste_departements) {
        foreach ($liste_departements as $departement) {
            $requete->bindParam(':did', $did);
            $requete->bindParam(':rid', $rid);
            $requete->bindParam(':departement', $departement);
            $requete->execute();
            echo $did . ". Département '$departement' de la région '$rid' inséré avec succès." . PHP_EOL;
            $did ++; // Incrémentation de l'ID;
        }
    }
    echo "Insertion des départements terminée avec succès." . PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des départements : " . $erreur->getMessage();
}
?>
