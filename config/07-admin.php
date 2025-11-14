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
    
    // Fonction de hachage pour les mots de passe
    function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Insertion de quelques exemples de champs dans la table 'admin'
    $sql_insert_admin = "INSERT INTO admin (brid, rid, nomComplet, email, password, role) VALUES 
                        (NULL, NULL, 'Administrateur Mass Diop', 'admin@example.com', '" . hashPassword('admin') . "', 'admin'),
                        (1, NULL,'Pr. Jury Anta Ndiaye', 'jury@example.com', '" . hashPassword('jury') . "', 'jury'),
                        (NULL, 1, 'Commission Électorale Régionale de DAKAR', 'cerdakar@example.com', '" . hashPassword('cerdakar') . "', 'cer'),
                        (NULL, 2, 'Commission Électorale Régionale de DIOURBEL', 'cerdiourbel@example.com', '" . hashPassword('cerdiourbel') . "', 'cer'),
                        (NULL, 3, 'Commission Électorale Régionale de Fatick', 'cerfatick@example.com', '" . hashPassword('cerfatick') . "', 'cer'),
                        (NULL, 4, 'Commission Électorale Régionale de Kaffrine', 'cerkaffrine@example.com', '" . hashPassword('cerkaffrine') . "', 'cer'),
                        (NULL, 5, 'Commission Électorale Régionale de Kaolack', 'cerkaolack@example.com', '" . hashPassword('cerkaolack') . "', 'cer'),
                        (NULL, 6, 'Commission Électorale Régionale de Kédougou', 'cerkedougou@example.com', '" . hashPassword('cerkedougou') . "', 'cer'),
                        (NULL, 7, 'Commission Électorale Régionale de Kolda', 'cerkolda@example.com', '" . hashPassword('cerkolda') . "', 'cer'),
                        (NULL, 8, 'Commission Électorale Régionale de Louga', 'cerlouga@example.com', '" . hashPassword('cerlouga') . "', 'cer'),
                        (NULL, 9, 'Commission Électorale Régionale de Matam', 'cermatam@example.com', '" . hashPassword('cermatam') . "', 'cer'),
                        (NULL, 10, 'Commission Électorale Régionale de Saint-Louis', 'cersaintlouis@example.com', '" . hashPassword('cersaintlouis') . "', 'cer'),
                        (NULL, 11, 'Commission Électorale Régionale de Sédhiou', 'cersedhiou@example.com', '" . hashPassword('cersedhiou') . "', 'cer'),
                        (NULL, 12, 'Commission Électorale Régionale de Tambacounda', 'certambacounda@example.com', '" . hashPassword('certambacounda') . "', 'cer'),
                        (NULL, 13, 'Commission Électorale Régionale de Thiès', 'certhies@example.com', '" . hashPassword('certhies') . "', 'cer'),
                        (NULL, 14, 'Commission Électorale Régionale de Ziguinchor', 'cerziguinchor@example.com', '" . hashPassword('cerziguinchor') . "', 'cer'),
                        (NULL, NULL, 'Commission Électorale Nationale Autonome', 'cena@example.com', '" . hashPassword('cena') . "', 'cena')";

    
    // Exécution de la requête SQL
    $connexion->exec($sql_insert_admin);
    
    echo "Données insérées dans la table 'admin' avec succès.".PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des exemples de champs dans la table 'admin' : " . $erreur->getMessage() . PHP_EOL;
}
?>
