<?php
// Inclure le fichier de configuration de la base de données
require_once __DIR__.'/includes/conx.php';

// Requête SQL pour obtenir les résultats les plus récents des bureaux
$query = "SELECT candidats.candidat, resultats.voix, resultats.date AS date, regions.region AS region,
                 departements.departement AS departement, communes.commune AS commune,
                 lieux.lieu AS lieu, bureaux.id AS bureau_id
          FROM candidats
          JOIN resultats ON candidats.id = resultats.cdid
          JOIN bureaux ON resultats.brid = bureaux.id
          JOIN lieux ON bureaux.lid = lieux.id
          JOIN communes ON lieux.cid = communes.id
          JOIN departements ON communes.did = departements.id
          JOIN regions ON departements.rid = regions.id
          ORDER BY bureau_id, date";

// Exécuter la requête
$stmt = $connexion->query($query);

// Tableau pour stocker les résultats
$results = [];

// Parcourir les résultats de la requête et les organiser
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $bureau_id = $row['bureau_id'];
    
    // Si le bureau n'existe pas encore dans les résultats, l'ajouter
    if (!isset($results[$bureau_id])) {
        $results[$bureau_id] = [
            'bureau_id' => $bureau_id,
            'region' => $row['region'],
            'departement' => $row['departement'],
            'commune' => $row['commune'],
            'lieu' => $row['lieu'],
            'candidates' => []
        ];
    }
    
    // Ajouter le candidat et le nombre de voix aux résultats
    $results[$bureau_id]['candidates'][] = [
        'name' => $row['candidat'],
        'voix' => $row['voix']
    ];
}

// Transformer les résultats en JSON et les renvoyer
header('Content-Type: application/json');
echo json_encode(array_values($results));
?>
