<?php
session_start();
include "connexion_bdd.php"; // Assurez-vous que ce fichier contient vos paramètres de connexion sécurisés

if (empty($_SESSION['is_admin']) || $_SESSION['user_email'] != "admin@admin") {
    header('Location: login.php'); // Redirection vers login si l'utilisateur n'est pas l'admin
    exit;
}

// Récupérer les informations des utilisateurs
$query = "SELECT utilisateur.idutilisateur,
 utilisateur.first_name, utilisateur.last_name, 
 utilisateur.email, utilisateur.profile_photo, 
 utilisateur.created_at, roles.role_name 
FROM utilisateur 
JOIN roles 
ON utilisateur.role_id = roles.role_id";
$result = mysqli_query($con, $query);

// Récupérer tous les messages avec les informations de l'utilisateur
$query_message = "SELECT messages.id, messages.message, 
messages.created_at, utilisateur.first_name, utilisateur.last_name, 
utilisateur.email, utilisateur.profile_photo 
FROM messages 
JOIN utilisateur
 ON messages.user_id = utilisateur.idutilisateur ORDER BY messages.created_at DESC";
$result_message = mysqli_query($con, $query_message);
?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
        }
    </style>

</head>

<body class="bg-gray-900">
    <section class="py-16 px-6 mx-auto max-w-screen-xl">
        <div class="text-center">
            <h1 class="mb-6 text-4xl font-extrabold text-white leading-none md:text-5xl lg:text-6xl">Voici la page
                administration</h1>
            <p class="mb-6 text-lg text-gray-400 lg:text-xl">Ici, vous pouvez superviser les utilisateurs et leurs
                activités dans le chat.</p>
        </div>

        <h2 class="text-3xl text-white font-bold my-6 text-center">Gestion des utilisateurs</h2>
        <p class="text-lg text-gray-400 mb-6 text-center">Ci-dessous se trouve la liste des utilisateurs actifs avec
            leurs rôles et options de gestion.</p>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Utilisateur</th>
                        <th scope="col" class="px-6 py-3">Rôle</th>
                        <th scope="col" class="px-6 py-3">Date de création du compte</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="flex items-center space-x-4 px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                <img class="w-10 h-10 rounded-full" src="<?= htmlspecialchars($user['profile_photo']) ?>"
                                    alt="User image">
                                <div>
                                    <div class="text-base font-semibold">
                                        <?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?>
                                    </div>
                                    <div class="font-normal text-gray-500"><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                            </th>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['role_name']) ?></td>
                            <td class="px-6 py-4"><?= date('Y-m-d H:i:s', strtotime($user['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <a href="javascript:void(0);"
                                    onclick="confirmSuppression('supprimer_user.php?id=<?= $user['idutilisateur'] ?>')"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Supprimer
                                    l'utilisateur</a>
                            </td>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="py-16 px-6 mx-auto max-w-screen-xl">
        <h2 class="text-3xl text-white font-bold my-6 text-center">Historique des messages</h2>
        <p class="text-lg text-gray-400 mb-6 text-center">Examinez l'historique des messages échangés par les
            utilisateurs et les actions pertinentes.</p>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Utilisateur</th>
                        <th scope="col" class="px-6 py-3">Message</th>
                        <th scope="col" class="px-6 py-3">Heure d'envoi</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_message)): ?>
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="w-10 h-10 rounded-full" src="<?= htmlspecialchars($row['profile_photo']) ?>"
                                        alt="User image">
                                    <div class="ml-3">
                                        <div class="text-base font-semibold">
                                            <?= htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($row['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['message']) ?></td>
                            <td class="px-6 py-4"><?= date('Y-m-d H:i:s', strtotime($row['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <a href="supprimer_message.php?id=<?= $row['id'] ?>"
                                    class="text-red-600 hover:text-red-900">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </section>



</body>



</html>

<script>
    function confirmSuppression(url) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.")) {
            window.location.href = url; // Redirige vers l'URL si confirmé
        }
        // Si non confirmé, ne fait rien et reste sur la même page
    }
</script>









</body>

</html>