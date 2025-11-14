<?php
    // Appeler la fonction pour récupérer les départements
    $departements = get_departement($connexion);

    if ($departements) {
        // Afficher le menu latéral avec les régions et leurs départements
        echo '<div class="sidebar">';
        echo '<ul class="nav flex-column">';
        echo '<li class="nav-item muted bg-dark mt-auto">';
        echo '<div class="d-flex">';
        echo '<a class="nav-link active align-self-center" href="#">Administration</a>';
        echo '<div class="ml-auto">';
        // Vérifie si une session est active
        if (!checkSession()) {
            // Bouton Connexion
            echo '<a href="home.php" class="btn btn-primary ml-1 btn-sm align-self-center"><i class="fas fa-plus"></i></a>';
        } else {
            // Bouton Déconnexion
            echo '<a href="home.php" class="btn btn-primary ml-1 btn-sm align-self-center"><i class="fas fa-home"></i></a>
            <a href="logout.php" class="btn btn-warning m-1 btn-sm align-self-center" onclick="return confirm(\'Voulez-vous vous déconnecter ?\')"><i class="fas fa-sign-out-alt"></i></a>';
        }
        echo '</div>';
        echo '</div>';
        
        echo '</li>';
          // Boucler à travers les départements pour afficher chaque région et ses départements
          $currentRegion = '';
          foreach ($departements as $departement) {
              $region = $departement['region'];
              $departementNom = $departement['departement'];
              $departementId = $departement['did'];
      
              // Vérifier si la région a changé
              if ($region !== $currentRegion) {
                  // Si c'est le cas, afficher la nouvelle région
                  if ($currentRegion !== '') {
                      // Fermer la liste des départements précédente
                      echo '</ul>';
                      echo '</li>';
                  }
      
                  // Ouvrir une nouvelle liste pour la nouvelle région
                  echo '<li class="nav-item bg-white">';
                  echo '<a class="nav-link" href="#" data-toggle="collapse" data-target="#' . strtolower($region) . '">' . $region . '</a>';
                  echo '<ul id="' . strtolower($region) . '" class="collapse">';
                  // Mettre à jour la région actuelle
                  $currentRegion = $region;
              }
      
              // Afficher le département
              echo '<li class="nav-item">';
              echo '<a class="nav-link dep-selection" href="#" id="'.$departementId.'">' . $departementNom . '</a>';
              echo '</li>';
          }
      
          // Fermer la liste des départements pour la dernière région
          echo '</ul>';
          echo '</li>';
        echo '</ul>';
      echo '</div>';
    } else {
        // Si aucun département n'a été trouvé, afficher un message d'erreur
        echo "Aucun département trouvé.";
    }
    ?>
