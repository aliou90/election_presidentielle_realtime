<?php
// Inclure le fichier de connexion à la base de données
require_once __DIR__.'/includes/conx.php';
include_once __DIR__.'/includes/requests.php';

// Utilisation de la fonction checkLoginSuccess
$email = $_POST['email']; // Assurez-vous de valider et nettoyer les données
$password = $_POST['password']; // Assurez-vous de valider et nettoyer les données
$role = $_POST['role']; // Assurez-vous de valider et nettoyer les données

if (checkLoginSuccess($email, $password, $role, $connexion)) {
    echo 'success';
} else {
    echo 'error';
}
?>
