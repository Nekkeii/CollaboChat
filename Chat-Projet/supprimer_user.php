
<?php
session_start();
include "connexion_bdd.php"; // Assurez-vous que ce fichier contient vos paramètres de connexion sécurisés

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Préparation de la requête de suppression
    $query = "DELETE FROM utilisateur WHERE idutilisateur = ?";
    $stmt = mysqli_prepare($con, $query);

    // Liaison du paramètre et exécution de la requête
    mysqli_stmt_bind_param($stmt, "i", $userId);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirection vers la page d'administration avec un message de succès
        header("Location: admin.php?success=1");
    } else {
        // Redirection vers la page d'administration avec un message d'erreur
        header("Location: admin.php?error=1");
    }
} else {
    // Redirection si aucun ID utilisateur n'est fourni
    header("Location: admin.php?error=2");
}

