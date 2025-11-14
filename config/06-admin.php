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
    $sql_insert_admin = "INSERT INTO admin (brid, rid, did, designation, email, password, role) VALUES 
                        (NULL, NULL, NULL, 'Administrateur Mass Diop', 'admin@example.com', '" . hashPassword('admin') . "', 'admin'),
                        (NULL, NULL, NULL, 'Commission Électorale Nationale Autonome', 'cena@example.com', '" . hashPassword('cena') . "', 'cena')";
    
    // Exécution de la requête SQL
    $connexion->exec($sql_insert_admin);
    
    echo "Administrateur National (CENA) ajouté avec succès.".PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des administrateurs et du cena dans la table 'admin' : " . $erreur->getMessage() . PHP_EOL;
}
?>

<?php
/* -------------------------------------------------------------------------
Insertion des administrateurs des régions
 -------------------------------------------------------------------------*/
try {
    
    // Récupérer les noms et les identifiants de région depuis la base de données
    $regions_query = $connexion->query("SELECT id, region FROM regions");
    $regions = $regions_query->fetchAll(PDO::FETCH_ASSOC);

    // Préparer la requête d'insertion
    $sql_insert_user = $connexion->prepare("INSERT INTO admin (brid, rid, did, designation, email, password, role) VALUES 
                            (NULL, :rid, NULL, :designation, :email, :password, 'cer')");

    // Insérer un utilisateur pour chaque région
    foreach ($regions as $region) {
        $rid = $region['id'];
        $region_name = $region['region'];

        // Nettoyer le nom de région pour l'utiliser dans l'email
        $region_name_cleaned = iconv('UTF-8', 'ASCII//TRANSLIT', $region_name);
        $region_name_cleaned = preg_replace('/[^a-zA-Z0-9\s]/', '', $region_name);

        // Générer l'email et le mot de passe
        $email = 'cer' . strtolower($region_name_cleaned) . '@example.com';
        $password = hashPassword('cer' . strtolower($region_name_cleaned));

        // Liaison des valeurs aux paramètres de la requête préparée
        $sql_insert_user->bindValue(':rid', $rid, PDO::PARAM_INT);
        $sql_insert_user->bindValue(':designation', "Commission Électorale Régionale de " . ucfirst($region_name));
        $sql_insert_user->bindValue(':email', $email);
        $sql_insert_user->bindValue(':password', $password);

        // Exécution de la requête SQL
        $sql_insert_user->execute();
    }

    echo "Administrateurs insérés pour chaque région avec succès.".PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des utilisateurs pour chaque région : " . $erreur->getMessage() . PHP_EOL;
}
?>


<?php
/* -------------------------------------------------------------------------
Insertion des administrateurs des départements
 -------------------------------------------------------------------------*/
try {    
    // Récupérer la liste de tous les départements depuis la base de données
    $dep_list_query = $connexion->query("SELECT id, departement FROM departements");
    $dep_list = $dep_list_query->fetchAll(PDO::FETCH_ASSOC);

    // Préparer la requête d'insertion
    $sql_insert_user = $connexion->prepare("INSERT INTO admin (brid, rid, did, designation, email, password, role) VALUES 
                            (NULL, NULL, :dep_id, :designation, :email, :password, 'ced')");

    // Insérer un utilisateur pour chaque département
    foreach ($dep_list as $departement) {
        $dep_id = $departement['id'];
        $dep_nom = $departement['departement'];

        // Nettoyer le nom du département pour l'utiliser dans l'e-mail
        $dep_nom_cleaned = iconv('UTF-8', 'ASCII//TRANSLIT', $dep_nom);
        $dep_nom_cleaned = preg_replace('/[^a-zA-Z0-9\s]/', '', $dep_nom_cleaned);
        
        $email_user = 'ced' . strtolower($dep_nom_cleaned) .  $dep_id . '@example.com';
        $password_user = hashPassword('ced' . strtolower($dep_nom_cleaned) .  $dep_id);

        // Exécution de la requête SQL avec les valeurs correspondantes
        $sql_insert_user->bindValue(':dep_id', $dep_id);
        $sql_insert_user->bindValue(':designation', "Commission Électorale Départementale de " . ucfirst($dep_nom_cleaned));
        $sql_insert_user->bindValue(':email', $email_user);
        $sql_insert_user->bindValue(':password', $password_user);

        // Exécution de la requête SQL
        $sql_insert_user->execute();
    }

    echo "Administrateurs insérés pour chaque département avec succès.".PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des utilisateurs pour chaque département : " . $erreur->getMessage() . PHP_EOL;
}
?>

<?php
/* -------------------------------------------------------------------------
Insertion des administrateurs des bureaux
 -------------------------------------------------------------------------*/
try {
    // Récupérer les identifiants des bureaux depuis la table 'bureaux'
    $bureaux_query = $connexion->query("SELECT id FROM bureaux");
    $bureaux_ids = $bureaux_query->fetchAll(PDO::FETCH_COLUMN);

    // Préparer la requête d'insertion
    $sql_insert_user = $connexion->prepare("INSERT INTO admin (brid, rid, did, designation, email, password, role) VALUES 
                            (:brid, NULL, NULL, :designation, :email, :password, 'bureau')");

    // Insérer un utilisateur pour chaque bureau
    foreach ($bureaux_ids as $bureau_id) {
        // Générer l'email et le mot de passe
        $email = 'bureau' . $bureau_id . '@example.com';
        $password = hashPassword('bureau' . $bureau_id);

        // Liaison des valeurs aux paramètres de la requête préparée
        $sql_insert_user->bindValue(':brid', $bureau_id, PDO::PARAM_INT);
        $sql_insert_user->bindValue(':designation', 'Bureau ' . $bureau_id);
        $sql_insert_user->bindValue(':email', $email);
        $sql_insert_user->bindValue(':password', $password);

        // Exécution de la requête SQL
        $sql_insert_user->execute();
    }

    echo "Administrateurs insérés pour chaque bureau avec succès.".PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des utilisateurs pour chaque bureau : " . $erreur->getMessage() . PHP_EOL;
}
?>
