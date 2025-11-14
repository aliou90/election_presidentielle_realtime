<?php
/* MISE EN PLACE DE LA BASE DE DONNÉES DU SYSTÈME */ 

// Informations de connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "presidentielles";

try {
    // Connexion à la base de données MySQL
    $open = new PDO("mysql:host=$serveur", $utilisateur, $mot_de_passe);
    
    // Configuration des options pour afficher les erreurs PDO
    try {
        $open->exec("CREATE DATABASE IF NOT EXISTS presidentielles");
        echo "Base de données créée avec succès.".PHP_EOL;
    } catch (\Throwable $th) {
        //throw $th;
        echo "Base de données existante.".PHP_EOL;
    }    

    // Connexion à la base de données MySQL
    $connexion = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);

    // Configuration des options pour afficher les erreurs PDO
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Création de la table 'regions'
    $sql_regions = "CREATE TABLE IF NOT EXISTS regions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        region ENUM('Dakar', 'Diourbel', 'Fatick', 'Kaffrine', 'Kaolack', 'Kédougou', 'Kolda', 'Louga', 'Matam', 'Saint-Louis', 'Sédhiou', 'Tambacounda', 'Thiès', 'Ziguinchor') NOT NULL
    )";
    $connexion->exec($sql_regions);
    echo "Table 'regions' créée avec succès.".PHP_EOL;

    // Création de la table 'departements'
    $sql_departements = "CREATE TABLE IF NOT EXISTS departements (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            rid INT NOT NULL,
                            departement VARCHAR(255) NOT NULL,
                            FOREIGN KEY (rid) REFERENCES regions(id)
                        )";
    $connexion->exec($sql_departements);
    echo "Table 'departements' créée avec succès.".PHP_EOL;
    
    // Création de la table 'communes'
    $sql_communes = "CREATE TABLE IF NOT EXISTS communes (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        did INT NOT NULL,
        commune VARCHAR(255) NOT NULL,
        FOREIGN KEY (did) REFERENCES departements(id)
    )";
    $connexion->exec($sql_communes);
    echo "Table 'communes' créée avec succès.".PHP_EOL;

    // Création de la table 'lieux'
    $sql_lieux = "CREATE TABLE IF NOT EXISTS lieux (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        cid BIGINT NOT NULL,
        lieu VARCHAR(255), 
        FOREIGN KEY (cid) REFERENCES communes(id)
    )";
    $connexion->exec($sql_lieux);
    echo "Table 'lieux' créée avec succès.".PHP_EOL;

    // Création de la table 'bureaux'
    $sql_bureaux = "CREATE TABLE IF NOT EXISTS bureaux (
        id BIGINT PRIMARY KEY,
        lid BIGINT NOT NULL,
        electeurs INT DEFAULT 0,
        votants INT,
        bblancs INT,
        bnulls INT,
        locked INT DEFAULT 0 CHECK (locked IN (0, 1)),
        FOREIGN KEY (lid) REFERENCES lieux(id)
    )";

    $connexion->exec($sql_bureaux);
    echo "Table 'bureaux' créée avec succès.".PHP_EOL;

    // Création de la table 'candidats'
    $sql_candidats = "CREATE TABLE IF NOT EXISTS candidats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        parti VARCHAR(255),
        abbr VARCHAR(255) NOT NULL,
        coalition VARCHAR(255),
        candidat VARCHAR(255) NOT NULL,
        img VARCHAR(255)
    )";
    $connexion->exec($sql_candidats);
    echo "Table 'candidats' créée avec succès.".PHP_EOL;

    // Création de la table 'resultats'
    $sql_resultats = "CREATE TABLE IF NOT EXISTS resultats (
        cdid INT,
        brid BIGINT,
        voix INT DEFAULT 0,
        date TIMESTAMP NOT NULL DEFAULT NOW(),
        PRIMARY KEY (cdid, brid),
        FOREIGN KEY (cdid) REFERENCES candidats(id),
        FOREIGN KEY (brid) REFERENCES bureaux(id)
    )";
    $connexion->exec($sql_resultats);
    echo "Table 'resultats' créée avec succès.".PHP_EOL;

        // Création de la table 'admin'
        $sql_admin = "CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            brid BIGINT UNIQUE,
            rid INT UNIQUE,
            did INT UNIQUE,
            designation VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('bureau', 'ced', 'cer', 'cena', 'admin') DEFAULT 'admin',
            FOREIGN KEY (brid) REFERENCES bureaux(id),
            FOREIGN KEY (rid) REFERENCES regions(id),
            FOREIGN KEY (did) REFERENCES departements(id)
        )";
        $connexion->exec($sql_admin);
        echo "Table 'admin' créée avec succès.".PHP_EOL ;
        
} catch(PDOException $erreur) {
    echo "Erreur lors de la création des tables : " . $erreur->getMessage();
}
?>
