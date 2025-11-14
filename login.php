<?php
// Importation de fichier de traitent BACKEND
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';

// Vérifie si une session est active
if (checkSession()) {
    // Rediriger vers home.php
    header('Location: home.php');
} 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Élection Présidentielle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include('includes/header.php'); ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Connexion</h2>
                <form id="loginForm" action="" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Entrer votre email" required>
                        </div>
                        <?php
                        // Récupérer les rôles
                        $roles = get_admin_roles($connexion);
                        ?>
                        <div class="form-group col-md-4">
                            <label for="role">Rôle</label>
                            <select class="form-control" id="role" name="role">
                                <?php
                                if ($roles !== false) {
                                    // Affichage des rôles dans le formulaire 
                                    foreach ($roles as $role) {
                                        echo '<option value="' . $role['role'] . '">' . ucfirst($role['role']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Entrer votre mot de passe" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </form>

                <div id="errorDisplay"></div>
                </div>
            </div>
        </div>


<!-- Inclure jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Connexion des utilisateurs-->
<script>
    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
            e.preventDefault(); // Empêche l'envoi du formulaire par défaut
            
            // Récupérer les données du formulaire
            var formData = $(this).serialize();

            // Envoyer les données via Ajax à login_process.php
            $.ajax({
                type: 'POST',
                url: 'login_process.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        // Rediriger vers la page home.php si la connexion réussit
                        window.location.href = 'home.php';
                    } else {
                        // Afficher les erreurs dans la zone d'affichage des erreurs
                        $('#errorDisplay').html('<div class="alert alert-danger" role="alert">Identifiants incorrects</div>');
                    }
                },
                error: function() {
                    // Afficher une erreur en cas d'échec de la requête Ajax
                    $('#errorDisplay').html('<div class="alert alert-danger" role="alert">Erreur de connexion. Veuillez réessayer plus tard.</div>');
                }
            });
        });
    });
</script>
<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<!-- Scripts d'automatisation -->
</body>
</html>
