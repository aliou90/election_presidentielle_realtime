<?php
// get_commune_data.php

// Inclure le fichier de connexion à la base de données
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';
$pdo = $connexion;

// Récupérer l'ID de la commune envoyé par Ajax
$communeId = $_POST['communeId'];

try {
    // Préparer la requête SQL pour récupérer les résultats des candidats pour la commune donnée
    $query = "SELECT candidats.abbr, resultats.voix 
              FROM candidats 
              INNER JOIN resultats ON candidats.id = resultats.cdid 
              WHERE resultats.cmid = :communeId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':communeId', $communeId);
    $stmt->execute();

    // Vérifier s'il y a des résultats
    if ($stmt->rowCount() > 0) {
        // Si des résultats sont trouvés, générer le tableau HTML
        echo '<h2>Résultats des candidats pour la commune</h2>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Abbr</th>';
        echo '<th>Voix</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['abbr'] . '</td>';
            echo '<td>' . $row['voix'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        // Si aucun résultat n'est trouvé, afficher un message d'erreur
        echo 'Aucun résultat trouvé pour cette commune.';
    }
} catch (PDOException $e) {
    // En cas d'erreur de la base de données, afficher l'erreur
    echo 'Erreur de la base de données : ' . $e->getMessage();
}
?>
