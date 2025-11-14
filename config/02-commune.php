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
    
    // Communes à insérer avec leurs départements correspondants
    $communes = array(
        //DAKAR
        // Communes de Dakar
        array('did' => 1, 'commune' => 'Plateau'),
        array('did' => 1, 'commune' => 'Medina'),
        array('did' => 1, 'commune' => 'Grand Dakar'),
        array('did' => 1, 'commune' => 'Parcelles Assainies'),
        array('did' => 1, 'commune' => 'Hann Bel-Air'),
        array('did' => 1, 'commune' => 'Almadies'),
        array('did' => 1, 'commune' => 'Mermoz-Sacré Cœur'),
        array('did' => 1, 'commune' => 'Ouakam'),
        array('did' => 1, 'commune' => 'Patte d\'Oie'),
        array('did' => 1, 'commune' => 'Ngor'),
        array('did' => 1, 'commune' => 'Liberté'),
        array('did' => 1, 'commune' => 'Dieuppeul-Derklé'),
        array('did' => 1, 'commune' => 'Sicap-Liberté'),
        array('did' => 1, 'commune' => 'HLM'),
        array('did' => 1, 'commune' => 'Grand-Yoff'),
        array('did' => 1, 'commune' => 'Colobane'),
        array('did' => 1, 'commune' => 'Fann-Point E-Amitié'),
        // Communes de Guédiawaye
        array('did' => 2, 'commune' => 'Médina Gounass'),
        array('did' => 2, 'commune' => 'Golf Sud'),
        array('did' => 2, 'commune' => 'Sam Notaire'),
        array('did' => 2, 'commune' => 'Demba Diop'),
        array('did' => 2, 'commune' => 'Darou Rahmane'),
        array('did' => 2, 'commune' => 'Wagouna'),
        array('did' => 2, 'commune' => 'Wakhinane Nimzatt'),
        array('did' => 2, 'commune' => 'Diamaguène Sicap Mbao'),
        array('did' => 2, 'commune' => 'Mbao'),
        array('did' => 2, 'commune' => 'Guédiawaye'),
        // Communes de Pikine
        array('did' => 3, 'commune' => 'Nord Foire'),
        array('did' => 3, 'commune' => 'Yeumbeul Nord'),
        array('did' => 3, 'commune' => 'Yeumbeul Sud'),
        array('did' => 3, 'commune' => 'Thiaroye Gare'),
        array('did' => 3, 'commune' => 'Thiaroye Sur Mer'),
        array('did' => 3, 'commune' => 'Djiddah Thiaroye Kao'),
        array('did' => 3, 'commune' => 'Keur Massar'),
        array('did' => 3, 'commune' => 'Pikine Ouest'),
        array('did' => 3, 'commune' => 'Pikine Est'),
        // Communes de Rufisque
        array('did' => 4, 'commune' => 'Rufisque Nord'),
        array('did' => 4, 'commune' => 'Rufisque Est'),
        array('did' => 4, 'commune' => 'Rufisque Ouest'),
        array('did' => 4, 'commune' => 'Bambilor'),
        array('did' => 4, 'commune' => 'Sangalkam'),

        // DIOURBEL
        // Communes de Diourbel
        array('did' => 5, 'commune' => 'Diourbel'),
        array('did' => 5, 'commune' => 'Ndindy'),
        array('did' => 5, 'commune' => 'Touba Ndorong'),

        // Communes de Bambey
        array('did' => 6, 'commune' => 'Bambey'),
        array('did' => 6, 'commune' => 'Baba Garage'),
        array('did' => 6, 'commune' => 'Diarère'),
        array('did' => 6, 'commune' => 'N\'diarème Limamoulaye'),
        array('did' => 6, 'commune' => 'Sagne'),
        array('did' => 6, 'commune' => 'Sagatta'),

        // Communes de Mbacké
        array('did' => 7, 'commune' => 'Mbacké'),
        array('did' => 7, 'commune' => 'Darou Khoudoss'),
        array('did' => 7, 'commune' => 'Darou Mousty'),
        array('did' => 7, 'commune' => 'Darou Salam'),
        array('did' => 7, 'commune' => 'Darou Salam Thiep'),
        array('did' => 7, 'commune' => 'Dalla-Dialloubé'),
        array('did' => 7, 'commune' => 'Taïba Moutoupha'),
        array('did' => 7, 'commune' => 'Thialène'),
        array('did' => 7, 'commune' => 'Touba Mosquée'),

        // FATICK
        // Communes de Fatick
        array('did' => 8, 'commune' => 'Fatick'),

        // // Communes de Foundiougne
        array('did' => 9, 'commune' => 'Foundiougne'),
        array('did' => 9, 'commune' => 'Djirnda'),
        array('did' => 9, 'commune' => 'Niodior'),
        array('did' => 9, 'commune' => 'Toubacouta'),

        // Communes de Gossas
        array('did' => 10, 'commune' => 'Gossas'),
        array('did' => 10, 'commune' => 'Baba Garage'),
        array('did' => 10, 'commune' => 'Mbirkilane'),

        // KAFFRINE
        // Communes de Kaffrine
        array('did' => 11, 'commune' => 'Kaffrine'),

        // Communes de Birkilane
        array('did' => 12, 'commune' => 'Birkilane'),
        array('did' => 12, 'commune' => 'Bamba Thialène'),
        array('did' => 12, 'commune' => 'Boki Diawe'),
        array('did' => 12, 'commune' => 'Lambaye'),
        array('did' => 12, 'commune' => 'Sagna'),

        // Communes de Koungheul
        array('did' => 13, 'commune' => 'Koungheul'),
        array('did' => 13, 'commune' => 'Dar Salam'),
        array('did' => 13, 'commune' => 'Gniby'),
        array('did' => 13, 'commune' => 'Khelcom'),
        array('did' => 13, 'commune' => 'Missirah Wadene'),


        // KAOLACK
        // Communes de Kaolack
        array('did' => 14, 'commune' => 'Kaolack'),

        // Communes de Guinguinéo
        array('did' => 15, 'commune' => 'Guinguinéo'),
        array('did' => 15, 'commune' => 'Bandafassi'),
        array('did' => 15, 'commune' => 'Gassane'),
        array('did' => 15, 'commune' => 'Gouloumbou'),
        array('did' => 15, 'commune' => 'Taïba Ndiaye'),

        // Communes de Nioro du Rip
        array('did' => 16, 'commune' => 'Nioro du Rip'),
        array('did' => 16, 'commune' => 'Bélé'),
        array('did' => 16, 'commune' => 'Dabaly'),
        array('did' => 16, 'commune' => 'Lour Escale'),
        array('did' => 16, 'commune' => 'Nguène Djiba'),

        // KÉDOUGOU
        // Communes de Kédougou
        array('did' => 17, 'commune' => 'Kédougou'),

        // Communes de Salemata
        array('did' => 18, 'commune' => 'Salemata'),
        array('did' => 18, 'commune' => 'Bandafassi'),
        array('did' => 18, 'commune' => 'Dindefelo'),
        array('did' => 18, 'commune' => 'Tomboronkoto'),

        // Communes de Saraya
        array('did' => 19, 'commune' => 'Saraya'),
        array('did' => 19, 'commune' => 'Dindefelo'),
        array('did' => 19, 'commune' => 'Salémata'),
        array('did' => 19, 'commune' => 'Santanatou Wouré'),

        //KOLDA
        // Communes de Kolda
        array('did' => 20, 'commune' => 'Kolda'),

        // Communes de Médina Yoro Foulah
        array('did' => 21, 'commune' => 'Médina Yoro Foulah'),
        array('did' => 21, 'commune' => 'Bandafassi'),
        array('did' => 21, 'commune' => 'Dabo'),
        array('did' => 21, 'commune' => 'Saré Bidji'),
        array('did' => 21, 'commune' => 'Saré Moussa Téfess'),
        array('did' => 21, 'commune' => 'Saré Samba Yaya'),

        // Communes de Vélingara
        array('did' => 22, 'commune' => 'Vélingara'),
        array('did' => 22, 'commune' => 'Dioulacolon'),
        array('did' => 22, 'commune' => 'Diouloulou'),
        array('did' => 22, 'commune' => 'Marsassoum'),
        array('did' => 22, 'commune' => 'Sinthiang Koundara'),

        // LOUGA
        // Communes de Louga
        array('did' => 23, 'commune' => 'Louga'),

        // Communes de Kébémer
        array('did' => 24, 'commune' => 'Kébémer'),
        array('did' => 24, 'commune' => 'Darou Khoudoss'),
        array('did' => 24, 'commune' => 'Mbédiène'),
        array('did' => 24, 'commune' => 'Mélakh'),

        // Communes de Linguère
        array('did' => 25, 'commune' => 'Linguère'),
        array('did' => 25, 'commune' => 'Dahra'),
        array('did' => 25, 'commune' => 'Sagatta Dioloff'),
        array('did' => 25, 'commune' => 'Yang Yang'),

        // MATAM
        // Communes de Matam
        array('did' => 26, 'commune' => 'Matam'),

        // Communes de Kanel
        array('did' => 27, 'commune' => 'Kanel'),
        array('did' => 27, 'commune' => 'Dodel'),
        array('did' => 27, 'commune' => 'Gamadji Saré'),
        array('did' => 27, 'commune' => 'Sinthiou Bamambé'),

        // Communes de Ranérou Ferlo
        array('did' => 28, 'commune' => 'Ranérou Ferlo'),
        array('did' => 28, 'commune' => 'Bokidiawé'),
        array('did' => 28, 'commune' => 'Démette'),
        array('did' => 28, 'commune' => 'Ndioum'),

        // SAINT-LOUIS
        // Communes de Saint-Louis
        array('did' => 29, 'commune' => 'Saint-Louis'),

        // Communes de Dagana
        array('did' => 30, 'commune' => 'Dagana'),
        array('did' => 30, 'commune' => 'Fanaye'),
        array('did' => 30, 'commune' => 'Linguère'),
        array('did' => 30, 'commune' => 'Ross Béthio'),

        // Communes de Podor
        array('did' => 31, 'commune' => 'Podor'),
        array('did' => 31, 'commune' => 'Aéré Lao'),
        array('did' => 31, 'commune' => 'Ndioum'),
        array('did' => 31, 'commune' => 'Thilogne'),

        // SÉDHIOU
        // Communes de Sédhiou
        array('did' => 32, 'commune' => 'Sédhiou'),

        // Communes de Bounkiling
        array('did' => 33, 'commune' => 'Bounkiling'),
        array('did' => 33, 'commune' => 'Diendé'),
        array('did' => 33, 'commune' => 'Djibanar'),
        array('did' => 33, 'commune' => 'Séléki'),

        // Communes de Goudomp
        array('did' => 34, 'commune' => 'Goudomp'),
        array('did' => 34, 'commune' => 'Ibel'),
        array('did' => 34, 'commune' => 'Kartiack'),
        array('did' => 34, 'commune' => 'Simbandi Balante'),

        // TAMBACOUNDA
        // Communes de Tambacounda
        array('did' => 35, 'commune' => 'Tambacounda'),

        // Communes de Bakel
        array('did' => 36, 'commune' => 'Bakel'),
        array('did' => 36, 'commune' => 'Kidira'),

        // Communes de Goudiry
        array('did' => 37, 'commune' => 'Goudiry'),
        array('did' => 37, 'commune' => 'Missirah'),

        // Communes de Koupentoum
        array('did' => 38, 'commune' => 'Koupentoum'),
        array('did' => 38, 'commune' => 'Bamba Thialène'),
        array('did' => 38, 'commune' => 'Kathiaba Wolof'),

        // THIES
        // Communes de Thiès
        array('did' => 39, 'commune' => 'Thiès'),

        // Communes de M'bour
        array('did' => 40, 'commune' => 'M\'bour'),
        array('did' => 40, 'commune' => 'Diass'),
        array('did' => 40, 'commune' => 'Saly Portudal'),
        array('did' => 40, 'commune' => 'Sindia'),

        // Communes de Tivaouane
        array('did' => 41, 'commune' => 'Tivaouane'),
        array('did' => 41, 'commune' => 'Pire'),

        // ZIGUINCHOR
        // Communes de Ziguinchor
        array('did' => 42, 'commune' => 'Ziguinchor'),

        // Communes de Bignona
        array('did' => 43, 'commune' => 'Bignona'),
        array('did' => 43, 'commune' => 'Kataba I'),
        array('did' => 43, 'commune' => 'Kataba II'),
        array('did' => 43, 'commune' => 'Tendouck'),

        // Communes de Oussouye
        array('did' => 44, 'commune' => 'Oussouye'),
        array('did' => 44, 'commune' => 'Kabrousse'),
        array('did' => 44, 'commune' => 'Loudia Ouolof'),

    );
    
    
    
    // Préparer la requête d'insertion
    $requete = $connexion->prepare("INSERT INTO communes (id, did, commune) VALUES (:cid, :did, :commune)");
    
    $cid = 1; // Initialisation de l'ID des communes
    // Parcourir le tableau des communes et insérer chaque commune dans la table
    foreach ($communes as $commune) {
        $requete->bindParam(':cid', $cid);
        $requete->bindParam(':did', $commune['did']);
        $requete->bindParam(':commune', $commune['commune']);
        $requete->execute();
        echo $cid . ". Commune '" . $commune['commune'] . "' insérée avec succès.".PHP_EOL;
        $cid ++; // Incrémentation de l'ID;
    }
    echo "Insertion des communes terminée avec succès." . PHP_EOL;
    
} catch(PDOException $erreur) {
    echo "Erreur lors de l'insertion des communes : " . $erreur->getMessage();
}
?>
