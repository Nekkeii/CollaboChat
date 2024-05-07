

<?php
session_start(); // Démarrage de la session pour accéder aux variables de session
include "connexion_bdd.php"; // Inclure le script de connexion à la base de données pour accéder à l'objet de connexion

// Vérification si l'ID du message est transmis via l'URL (méthode GET)
if (isset($_GET['id'])) {  
    $message_id = $_GET['id']; // Récupération de l'ID du message à supprimer
    $query = "DELETE FROM messages WHERE id = ?";  // Préparation de la requête SQL de suppression

    // Préparation de la requête SQL pour exécution
    $stmt = $con->prepare($query); // L'objet $con est votre connexion à la base de données, assumé défini dans 'connexion_bdd.php'
    $stmt->bind_param("i", $message_id); // Liaison du paramètre de type entier (i pour integer) à la variable $message_id

    // Exécution de la requête préparée
    $stmt->execute();

    // Vérification du nombre de lignes affectées pour confirmer la suppression
    if ($stmt->affected_rows > 0) {
        echo "Message supprimé avec succès."; // Affichage d'un message de succès si la suppression a réussi
    } else {
        echo "Erreur lors de la suppression du message."; // Message d'erreur si la suppression a échoué
    }
    header("Location: admin.php"); // Redirection vers la page d'administration après la tentative de suppression
    exit; // Arrêt du script pour éviter l'exécution de code supplémentaire après la redirection
}
