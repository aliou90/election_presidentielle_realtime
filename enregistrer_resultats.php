<?php
// Vérifier si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Importer les fichiers de connexion et les fonctions de requête du backend
    include_once(__DIR__.'/includes/conx.php');
    include_once(__DIR__.'/includes/requests.php');

    // Récupérer les données envoyées par la requête Ajax
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérifier si des données ont été envoyées
    if (empty($data)) {
        // Envoyer une réponse JSON en cas de données manquantes
        echo json_encode(array("success" => false, "message" => "Aucune donnée n'a été reçue."));
        exit;
    }

    // Extraire l'ID du bureau et les résultats des candidats
    $bureauId = $data['bureauId'];
    $resultats = $data['resultats'];
    $bblancs = $data['bblancs'];
    $bnulls = $data['bnulls'];

    // Vérifier si toutes les voix sont des nombres et calculer la somme
    $totalVoix = 0;
    foreach ($resultats as $candidatId => $voix) {
        if (!is_numeric($voix)) {
            // Envoyer une réponse JSON en cas de voix non numérique
            echo json_encode(array("success" => false, "message" => "Les voix doivent être des nombres."));
            exit;
        }
        $totalVoix += $voix; // Ajout au toal des voix
    }

    // Vérifier si bblancs et bnulls sont des nombres
    if (!is_numeric($bblancs) || !is_numeric($bnulls)) {
        // Envoyer une réponse JSON en cas de voix non numérique
        echo json_encode(array("success" => false, "message" => "Les bulletins blancs et nulls doivent être des nombres."));
        exit;
    }

    // Traitement des données et insertion ou modification dans la base de données
    try {
        // Parcourir les résultats et insérer ou mettre à jour dans la base de données
        foreach ($resultats as $candidatId => $voix) {
            // Vérifier si le candidat a déjà un enregistrement dans la base de données pour ce bureau
            $requete_verification = $connexion->prepare("SELECT COUNT(*) FROM resultats WHERE brid = :brid AND cdid = :cdid");
            $requete_verification->bindParam(':brid', $bureauId);
            $requete_verification->bindParam(':cdid', $candidatId);
            $requete_verification->execute();
            $nombre_resultats = $requete_verification->fetchColumn();
            
            // Afficher les variables pour le débogage
            // echo "bureauId: " . $bureauId . ", candidatId: " . $candidatId . ", voix: " . $voix . "<br>";

            if ($nombre_resultats == 0) {
                // Insertion des résultats pour un nouveau candidat
                $requete_insertion = $connexion->prepare("INSERT INTO resultats (brid, cdid, voix) VALUES (:brid, :cdid, :voix)");
                $requete_insertion->bindParam(':brid', $bureauId);
                $requete_insertion->bindParam(':cdid', $candidatId);
                $requete_insertion->bindParam(':voix', $voix);
                $requete_insertion->execute();
            } else {
                // Mise à jour des résultats pour un candidat existant
                $requete_mise_a_jour = $connexion->prepare("UPDATE resultats SET voix = :voix WHERE brid = :brid AND cdid = :cdid");
                $requete_mise_a_jour->bindParam(':brid', $bureauId);
                $requete_mise_a_jour->bindParam(':cdid', $candidatId);
                $requete_mise_a_jour->bindParam(':voix', $voix);
                $requete_mise_a_jour->execute();
            }
        }
        // Mise à jour pour désactiver le bureau en le verrouillant
        $requete_lock_bureau = $connexion->prepare("UPDATE bureaux SET locked = 1, votants = :vt, bblancs = :bb, bnulls = :bn WHERE id = :brid");
        $votants = $totalVoix + $bblancs + $bnulls;
        $requete_lock_bureau->bindParam(':vt', $votants);
        $requete_lock_bureau->bindParam(':bb', $bblancs);
        $requete_lock_bureau->bindParam(':bn', $bnulls);
        $requete_lock_bureau->bindParam(':brid', $bureauId);
        $requete_lock_bureau->execute();


        // Envoyer une réponse JSON indiquant le succès de l'opération
        echo json_encode(array("success" => true, "message" => "Les résultats ont été enregistrés avec succès."));
    } catch (PDOException $erreur) {
        // Envoyer une réponse JSON en cas d'erreur
        echo json_encode(array("success" => false, "message" => "Erreur lors de l'enregistrement des résultats : " . $erreur->getMessage()));
    }
} else {
    // Si la requête n'est pas de type POST, envoyer une réponse JSON d'erreur
    echo json_encode(array("success" => false, "message" => "La requête doit être de type POST."));
}
?>
