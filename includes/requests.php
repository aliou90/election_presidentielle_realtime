<?php
// Inclure le fichier de connexion à la base de données
require_once 'conx.php';
// Maintenant vous pouvez utiliser la connexion à la base de données ($connexion) dans ce script
?>
<?php
// Liste des régions
function get_regions($connexion) {
    try {
        // Préparer la requête de sélection
        $requete = $connexion->prepare("SELECT * FROM regions");
        
        // Exécuter la requête
        $requete->execute();
        
        // Récupérer tous les résultats
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultats;
        
    } catch(PDOException $erreur) {
        echo "Erreur lors de la récupération des départements : " . $erreur->getMessage();
        return false;
    }
}
// Liste des départements
function get_departement($connexion) {
    try {
        // Préparer la requête de sélection
        $requete = $connexion->prepare("SELECT regions.id AS rid, regions.region AS region, departements.departement AS departement, departements.id AS did 
                                        FROM regions INNER JOIN departements ON regions.id = departements.rid");
        
        // Exécuter la requête
        $requete->execute();
        
        // Récupérer tous les résultats
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultats;
        
    } catch(PDOException $erreur) {
        echo "Erreur lors de la récupération des départements : " . $erreur->getMessage();
        return false;
    }
}

// Liste des départements d'une région
function get_region_departements($connexion, $region_id) {
    try {
        // Préparer la requête de sélection
        $requete = $connexion->prepare("SELECT regions.id AS rid, regions.region AS region, departements.departement AS departement, departements.id AS did 
                                        FROM regions INNER JOIN departements ON regions.id = departements.rid
                                        WHERE regions.id = :regionId ");
        
        // Exécuter la requête
        $requete->bindParam(':regionId', $region_id);
        $requete->execute();
        
        // Récupérer tous les résultats
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultats;
        
    } catch(PDOException $erreur) {
        echo "Erreur lors de la récupération des départements de la région : " . $erreur->getMessage();
        return false;
    }
}

// Liste des départements d'une région
function get_departement_communes($connexion, $dept_id) {
    try {
        // Préparer la requête de sélection
        $requete = $connexion->prepare("SELECT departements.id AS did, departements.departement AS departement, communes.commune AS commune, communes.id AS cid 
                                        FROM departements INNER JOIN communes ON departements.id = communes.did
                                        WHERE departements.id = :departementId ");
        
        // Exécuter la requête
        $requete->bindParam(':departementId', $dept_id);
        $requete->execute();
        
        // Récupérer tous les résultats
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultats;
        
    } catch(PDOException $erreur) {
        echo "Erreur lors de la récupération des communes du département : " . $erreur->getMessage();
        return false;
    }
}

// Recupérer bureau
function get_bureau($connexion, $bureau_id) {
    try {
        // Préparer la requête de sélection
        $requete = $connexion->prepare("SELECT regions.region, departements.departement, communes.commune, lieux.lieu, CONCAT('Bureau de vote ', bureaux.id) AS bureau, bureaux.id AS brid, bureaux.votants, bureaux.bblancs, bureaux.bnulls   
                                        FROM regions JOIN departements ON regions.id = departements.rid 
                                        JOIN communes ON departements.id = communes.did
                                        JOIN lieux ON communes.id = lieux.cid
                                        JOIN bureaux ON lieux.id = bureaux.lid
                                        WHERE bureaux.id = :bureauId ");
        
        // Exécuter la requête
        $requete->bindParam(':bureauId', $bureau_id);
        $requete->execute();
        
        // Récupérer tous les résultats
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultats;
        
    } catch(PDOException $erreur) {
        echo "Erreur lors de la récupération du bureau : " . $erreur->getMessage();
        return false;
    }
}

# Récupérer voix des candidats pour une commune
function get_voix($connexion, $communeID) {
    try {
        // Préparation de la requête SQL pour récupérer les voix pour chaque candidat associé à la commune
        $query_voix = "SELECT resultats.voix FROM candidats INNER JOIN resultats ON candidats.id = resultats.cdid WHERE resultats.cmid = :communeId";
        $stmt_voix = $connexion->prepare($query_voix);
        $stmt_voix->bindParam(':communeId', $communeID);
        $stmt_voix->execute();

        // Vérifier s'il y a des résultats
        if ($stmt_voix->rowCount() > 0) {
            // Retourner les voix sous forme de tableau associatif
            return $stmt_voix->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si aucun résultat n'est trouvé, retourner un tableau vide
            return array();
        }
    } catch (PDOException $e) {
        // En cas d'erreur de la base de données, afficher l'erreur
        echo 'Erreur de la base de données : '.$e->getMessage();
        return array(); // Retourner un tableau vide en cas d'erreur
    }
}


// Candidats et leurs résultats avec pourcentages
function get_candidats($connexion) {
    try {
        // Préparation de la requête SQL pour récupérer les candidats avec leurs résultats
        $query = "SELECT candidats.*, SUM(resultats.voix) AS total_voix,
            ROUND(SUM(resultats.voix) / (SELECT SUM(voix) FROM resultats) * 100, 2) AS pourcentage
            FROM candidats
            LEFT JOIN resultats ON candidats.id = resultats.cdid
            GROUP BY candidats.id";
        $stmt = $connexion->query($query);

        // Vérifier s'il y a des résultats
        if ($stmt->rowCount() > 0) {
            // Retourner les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si aucun résultat n'est trouvé, retourner un tableau vide
            return array();
        }
    } catch (PDOException $e) {
        // En cas d'erreur de la base de données, afficher l'erreur
        echo 'Erreur de la base de données : '.$e->getMessage();
        return array(); // Retourner un tableau vide en cas d'erreur
    }
}


// 2  Meilleurs candidats
function best_candidats($connexion) {
    try {
        // Préparation de la requête SQL pour récupérer les deux meilleurs candidats
        $query = "SELECT candidats.*, SUM(resultats.voix) AS total_voix,
            ROUND(SUM(resultats.voix) / (SELECT SUM(voix) FROM resultats) * 100, 2) AS pourcentage
            FROM candidats
            LEFT JOIN resultats ON candidats.id = resultats.cdid
            GROUP BY candidats.id
            ORDER BY total_voix DESC
            LIMIT 2";
        $stmt = $connexion->query($query);

        // Vérifier s'il y a des résultats
        if ($stmt->rowCount() > 0) {
            // Retourner les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si aucun résultat n'est trouvé, retourner un tableau vide
            return array();
        }
    } catch (PDOException $e) {
        // En cas d'erreur de la base de données, afficher l'erreur
        echo 'Erreur de la base de données : '.$e->getMessage();
        return array(); // Retourner un tableau vide en cas d'erreur
    }
}

// Fonction pour récupérer les rôles depuis la base de données
function get_admin_roles($connexion) {
    try {
        // Préparation de la requête SQL
        $query = "SELECT DISTINCT role FROM admin";
        
        // Exécution de la requête
        $stmt = $connexion->query($query);
        
        // Récupération des résultats sous forme de tableau associatif
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fermeture du curseur
        $stmt->closeCursor();
        
        // Retourner les rôles récupérés
        return $roles;
        
    } catch(PDOException $erreur) {
        // En cas d'erreur, afficher un message d'erreur
        echo "Erreur lors de la récupération des rôles : " . $erreur->getMessage();
        return false;
    }
}

// Recupérer les resultats du Bureau
function get_bureau_results($connexion, $bureau_id) {
    try {
        // Préparer la requête de sélection
        $requete = $connexion->prepare("SELECT candidats.candidat, resultats.cdid AS cdid, resultats.voix AS voix, resultats.brid AS brid, bureaux.locked
                                        FROM candidats 
                                        LEFT JOIN resultats ON candidats.id = resultats.cdid 
                                        LEFT JOIN bureaux ON resultats.brid = bureaux.id
                                        WHERE bureaux.id = :bureauId");
        
        // Exécuter la requête
        $requete->bindParam(':bureauId', $bureau_id);
        $requete->execute();
        
        // Récupérer tous les résultats
        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultats;
        
    } catch(PDOException $erreur) {
        echo "Erreur lors de la récupération des résultats du bureau : " . $erreur->getMessage();
        return false;
    }
}


// Vérifier connexion 
function checkLoginSuccess($email, $password, $role, $connexion) {
    // Valider l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Email invalide
    }

    // Préparez votre requête SQL pour récupérer le mot de passe haché de l'utilisateur
    $sql = "SELECT designation, brid, rid, did, password, role FROM admin WHERE email = :email";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Récupérer le résultat de la requête
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // Vérifier si le mot de passe haché correspond au mot de passe saisi par l'utilisateur
        if (password_verify($password, $row['password']) && $row['role'] === $role) {
            // Démarrer la session
            session_start();
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['designation'] = $row['designation'];
            $_SESSION['brid'] = $row['brid'];
            $_SESSION['rid'] = $row['rid'];
            $_SESSION['did'] = $row['did'];
            return true; // Connexion réussie
        }
    }
    
    return false; // Identifiants incorrects
}

// Virification de session
function checkSession() {
    // Démarre une nouvelle session ou reprend une session existante
    session_start();

    // Vérifie si la variable de session 'email' est définie
    if (isset($_SESSION['email'])) {
        return true; // La session est active
    } else {
        return false; // La session n'est pas active
    }
}

?>
