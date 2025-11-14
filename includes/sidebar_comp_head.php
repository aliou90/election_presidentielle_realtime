<?php 
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
?>