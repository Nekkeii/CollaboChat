
<?php
// Démarrage ou récupération de la session courante pour accéder aux variables de session
session_start();

// Vérifier si l'utilisateur n'est pas connecté en contrôlant la présence de 'user_email' dans la session
if (!isset($_SESSION['user_email'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("location:login.php");
    exit; // Arrêt du script pour éviter l'exécution du reste du code
}

// Récupérer l'email de l'utilisateur connecté à partir des variables de session
$user_email = $_SESSION['user_email'];

// Inclure le fichier de connexion à la base de données
include ("connexion_bdd.php");

// Préparation de la requête SQL pour récupérer les informations de l'utilisateur connecté
$stmt = $con->prepare("SELECT utilisateur.idutilisateur, utilisateur.first_name, utilisateur.last_name, utilisateur.profile_photo, utilisateur.email,
 roles.role_name FROM utilisateur JOIN roles ON utilisateur.role_id = roles.role_id WHERE utilisateur.email = ?");
$stmt->bind_param("s", $user_email); // Sécurisation de l'entrée utilisateur par liaison de paramètre
$stmt->execute(); // Exécution de la requête
$result = $stmt->get_result(); // Récupération du résultat de la requête
$user = $result->fetch_assoc(); // Transformation du résultat en tableau associatif

// Redirection si aucune information utilisateur n'est trouvée (l'utilisateur n'existe pas ou session corrompue)
if (!$user) {
    header("location:login.php");
    exit;
}

// Traitement du formulaire d'envoi de message
if (isset($_POST['send'], $_POST['message']) && !empty($_POST['message'])) {
    $message = trim($_POST['message']); // Nettoyage du message envoyé
    $stmt = $con->prepare("INSERT INTO messages (user_id, message, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user['idutilisateur'], $message); // Liaison des paramètres à la requête préparée
    $stmt->execute(); // Exécution de la requête
    header('Location: chat.php'); // Redirection vers chat.php pour éviter le rechargement du formulaire
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title><?= htmlspecialchars($user['first_name']) ?> | CHAT</title>
    <link rel="stylesheet" href="stle.css">
</head>

<style>
    .chat-message {
        margin: 12px 0;
        /* Augmenter le margin vertical pour plus d'espace */
        padding: 16px;
        /* Augmenter le padding pour agrandir les bulles */
        font-size: 1rem;
        /* Taille de police standard pour une meilleure lisibilité */
        line-height: 1.5;
        /* Espace entre les lignes */
        border-radius: 10px;
        /* Coins arrondis pour un look plus doux */
        max-width: none;

    }

    body {
        font-family: 'Barlow', sans-serif;
    }
</style>
</head>

<body class="bg-gray-900 text-white font-sans flex items-center justify-center min-h-screen">
    <div class="chat w-full max-w-4xl p-6 bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="user-info flex items-center justify-between p-3 border-b border-gray-700">
            <div class="flex items-center gap-4">
                <img src="<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Picture"
                    class="w-10 h-10 rounded-full">
                <div class="font-medium dark:text-white">
                    <div><?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?></div>
                    <div class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($user_email) ?></div>
                    <!-- Afficher le rôle ici -->
                    <span
                        class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                        <span class="w-1 h-1 me-1 bg-green-500 rounded-full"></span>
                                 <?= htmlspecialchars($user['role_name']) ?>
                    </span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <!-- Bouton Modifier Profil -->
                <a href="modifierprofil.php"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 
                    focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Modifier
                    Profil</a>
                <!-- Bouton Déconnexion -->
                <a href="deconnexion.php"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 
                    focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Déconnexion</a>
            </div>
        </div>

        <div class="messages_box flex-1 p-4 bg-gray-700 rounded overflow-y-auto my-2" style="height: 65vh;">
            Chargement...
        </div>
        <form action="" class="send_message flex p-3" method="POST">
            <textarea name="message"
                class="flex-grow p-4 rounded bg-gray-600 text-white border border-gray-600 focus:border-blue-500"
                placeholder="Votre message" style="height: 10vh;"></textarea>
            <input type="submit" value="Envoyer" name="send"
                class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded cursor-pointer">
        </form>
    </div>

    <script>
    // Déclaration de la variable `message_box` pour cibler l'élément HTML qui contient les messages du chat.
    var message_box = document.querySelector('.messages_box');

    // Utilisation de `setInterval` pour créer une fonction qui s'exécute à intervalles réguliers.
    // Ici, elle est configurée pour s'exécuter toutes les 500 millisecondes (0.5 seconde).
    setInterval(function () {
        // Création d'un nouvel objet XMLHttpRequest, qui permet de faire des requêtes HTTP
        // sans recharger la page.
        var xhttp = new XMLHttpRequest();

        // Définition de la fonction à exécuter à chaque changement d'état de la requête.
        xhttp.onreadystatechange = function () {
            // `this.readyState == 4` signifie que la requête est terminée et que la réponse est prête.
            // `this.status == 200` signifie que la requête a réussi sans erreurs.
            if (this.readyState == 4 && this.status == 200) {
                // Mise à jour du contenu de `message_box` avec les données reçues en réponse,
                // qui sont accessibles via `this.responseText`.
                message_box.innerHTML = this.responseText;
            }
        };

        // Configuration de la requête avec la méthode `GET` pour récupérer les données depuis `messages.php`.
        // Le troisième paramètre `true` indique que la requête doit être effectuée de manière asynchrone,
        // ce qui permet à la page de continuer à fonctionner normalement pendant que la requête est en cours.
        xhttp.open("GET", "messages.php", true);

        // Envoi de la requête au serveur.
        xhttp.send();
    }, 500); // Fin de la fonction `setInterval`.
</script>
</body>

</html>