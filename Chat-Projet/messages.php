<?php
session_start(); // Démarrage de la session pour accéder aux variables de session

// Vérification de l'existence de l'ID de l'utilisateur dans la session pour s'assurer que l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    include "connexion_bdd.php"; // Inclure la connexion à la base de données

    // Requête SQL pour récupérer les messages et les informations associées des utilisateurs et de leur rôle
    $req = mysqli_query($con, "SELECT messages.id, messages.message, messages.created_at, utilisateur.idutilisateur, utilisateur.first_name, utilisateur.last_name, utilisateur.profile_photo, roles.role_name FROM messages JOIN utilisateur ON messages.user_id = utilisateur.idutilisateur JOIN roles ON utilisateur.role_id = roles.role_id ORDER BY messages.id DESC");

    // Vérification si la requête n'a retourné aucun message
    if (mysqli_num_rows($req) == 0) {
        echo "<div class='text-white'>Messagerie vide</div>"; // Affichage d'un message si aucune correspondance n'est trouvée
    } else {
        // Boucle pour traiter chaque message récupéré de la base de données
        while ($row = mysqli_fetch_assoc($req)) {

            // Formater la date de création du message et le role de l'utilisateur 
            $formatted_date = date('H:i', strtotime($row['created_at'])); 
            $role_label = "<span class='bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 
            py-0.5 rounded dark:bg-gray-700 dark:text-gray-300'>" . htmlspecialchars($row['role_name']) . "</span>";

            // Vérification si le message a été envoyé par l'utilisateur connecté
            if ($row['idutilisateur'] == $_SESSION['user_id']) {
                // Bloc HTML pour l'affichage des messages de l'utilisateur connecté
                ?>
                <div class="flex justify-end items-end">
                    <div class="chat-message bg-blue-300 rounded-lg max-w-xs">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white font-semibold">
                                Vous <span class="text-gray-400 text-xs"><?php echo $formatted_date; ?></span>
                            </p>
                            <p class="text-white text-sm mt-1">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </p>
                            <div
                                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 
                                py-0.5 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                <?php echo $role_label; ?>
                            </div>
                        </div>
                    </div>
                    <img src="<?php echo $row['profile_photo']; ?>" alt="My profile" class="w-8 h-8 rounded-full ml-2">
                </div>
                <?php
            } else {
                // Bloc HTML pour l'affichage des messages des autres utilisateurs
                ?>
                <div class="flex justify-start items-end">
                    <img src="<?php echo $row['profile_photo']; ?>" alt="Profile picture" class="w-8 h-8 rounded-full mr-2">
                    <div class="chat-message bg-gray-800 rounded-lg max-w-xs">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-blue-300 font-semibold">
                                <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?> <span
                                    class="text-gray-400 text-xs"><?php echo $formatted_date; ?></span>
                            </p>
                            <p class="text-white text-sm mt-1">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </p>
                            <div
                                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 
                                py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                                <?php echo $role_label; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}
?>