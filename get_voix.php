<?php
// Inclure le fichier de connexion à la base de données
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';

// Vérifier si l'ID de la commune est envoyé via POST
if(isset($_POST['communeId'])) {
    // Récupérer l'ID de la commune depuis la requête POST
    $communeId = $_POST['communeId'];

    try {
        // Préparer la requête SQL pour récupérer les voix des candidats associés à cette commune
        $query = "SELECT resultats.voix 
                  FROM candidats 
                  INNER JOIN resultats ON candidats.id = resultats.cdid 
                  WHERE resultats.cmid = :communeId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':communeId', $communeId);
        $stmt->execute();

        // Créer un tableau associatif pour stocker les voix des candidats
        $voixCandidats = array();

        // Parcourir les résultats de la requête et stocker les voix dans le tableau
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $voixCandidats[] = $row;
        }

        // Retourner les voix des candidats au format JSON
        echo json_encode($voixCandidats);
    } catch (PDOException $e) {
        // En cas d'erreur de la base de données, afficher l'erreur
        echo json_encode(array('error' => 'Erreur de la base de données : ' . $e->getMessage()));
    }
} else {
    // Si l'ID de la commune n'est pas envoyé, retourner un message d'erreur
    echo json_encode(array('error' => 'ID de commune non spécifié'));
}
?>
