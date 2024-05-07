<?php
//démarer la session
session_start();
include "connexion_bdd.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>insciption</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
        }
    </style>
</head>


<?php



// Vérifier si le formulaire a été soumis via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des valeurs des champs du formulaire
    $firstname = $_POST['user-firstname'];
    $lastname = $_POST['user-lastname'];
    $email = $_POST['user-email'];
    $password = $_POST['user-password'];
    $confirm_password = $_POST['user-confirm-password'];
    $role_id = $_POST['role_id'];

    // Configuration du répertoire de téléchargement pour les photos de profil
    $upload_dir = "uploads/";
    $profile_photo_path = "";

    // Traitement du téléchargement de la photo de profil
    if (isset($_FILES['profile-photo']) && $_FILES['profile-photo']['error'] == 0) {
        $temp_name = $_FILES['profile-photo']['tmp_name'];
        $file_name = basename($_FILES['profile-photo']['name']);
        $path = $upload_dir . $file_name;
        // Déplacer le fichier téléchargé vers le répertoire de destination
        if (move_uploaded_file($temp_name, $path)) {
            $profile_photo_path = $path;
        } else {
            $error = "Erreur lors du téléchargement de l'image.";
        }
    }

    // Validation des champs requis pour l'inscription
    if (!empty($email) && !empty($password) && !empty($confirm_password) && empty($error)) {
        // Vérification de la correspondance des mots de passe
        if ($password !== $confirm_password) {
            $error = "Les mots de passe ne correspondent pas !";
        } else {
            // Préparation de la requête pour vérifier si l'email est déjà utilisé
            $email_check_query = "SELECT * FROM utilisateur WHERE email = ?";
            $stmt = $con->prepare($email_check_query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Inscription de l'utilisateur si l'email n'est pas déjà utilisé
            if ($result->num_rows == 0) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $insert_query = "INSERT INTO utilisateur (first_name, last_name, email, password, profile_photo, role_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($insert_query);
                $stmt->bind_param("sssssi", $firstname, $lastname, $email, $password_hash, $profile_photo_path, $role_id);
                $success = $stmt->execute();

                // Redirection et message en cas de succès
                if ($success) {
                    $_SESSION['message'] = "Votre compte a été créé avec succès !";
                    header("Location: inscriptionOK.php");
                } else {
                    $error = "L'inscription a échoué. Veuillez réessayer.";
                }
            } else {
                $error = "Cet email est déjà utilisé par un autre compte.";
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs requis.";
    }
}
?>




<body class="bg-gray-900 flex flex-col items-center justify-center h-screen">
    <!-- Contactez-nous -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="max-w-2xl lg:max-w-5xl mx-auto">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-white sm:text-4xl dark:text-white">
                    Inscription
                </h1>
                <p class="mt-1 text-white dark:text-white">
                    Veuillez créer votre compte en remplissant les champs
                    nécessaires. Assurez-vous d'utiliser un mot de passe fort pour sécuriser votre accès.
                </p>

            </div>

            <div class="mt-12 grid items-center lg:grid-cols-2 gap-6 lg:gap-16">
                <!-- Carte -->
                <div
                    class="flex flex-col border rounded-xl p-4 sm:p-6 lg:p-8 dark:border-neutral-700 w-96 bg-gray-800 p-8 rounded-lg shadow-lg">
                    <h2 class="mb-8 text-xl font-semibold text-white dark:text-white">
                        Créez votre compte !
                    </h2>

                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="grid gap-4">

                            <div class="text-white">
                                <?php if (!empty($error))
                                    echo $error; ?>
                            </div>

                            <div>
                                <label for="profile-photo" class=" block text-sm text-gray-400">Photo de profil</label>
                                <label for="profile-photo" class="sr-only">Photo de profil</label>
                                <input type="file" name="profile-photo" id="profile-photo" class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4
                                     file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100" accept="image/*">
                            </div>

                            <!-- Grille pour les inputs -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="user-firstname" class="sr-only">Prénom</label>
                                    <input type="text" name="user-firstname" id="user-firstname"
                                        class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Prénom">
                                </div>

                                <div>
                                    <label for="user-lastname" class="sr-only">Nom de famille</label>
                                    <input type="text" name="user-lastname" id="user-lastname"
                                        class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Nom de famille">
                                </div>
                            </div>

                            <div>
                                <label for="user-email" class="sr-only">E-mail</label>
                                <input type="email" name="user-email" id="user-email" autocomplete="email"
                                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="E-mail">
                            </div>

                            <div>
                                <label for="role" class="block text-sm text-gray-400">Rôle:</label>
                                <select name="role_id" id="role" required
                                    class="w-full p-3 bg-gray-700 text-white rounded outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sélectionnez votre rôle...</option>
                                    <option value="1">Développeur Backend</option>
                                    <option value="2">Développeur Frontend</option>
                                    <option value="3">Chef de projet</option>
                                    <option value="4">Administrateur réseaux</option>
                                </select>
                            </div>




                            <div>
                                <label for="user-password" class="sr-only">Mot de passe</label>
                                <input type="password" name="user-password" id="user-password"
                                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Mot de passe">
                            </div>

                            <div>
                                <label for="user-confirm-password" class="sr-only">Confirmer le mot de passe</label>
                                <input type="password" name="user-confirm-password" id="user-confirm-password"
                                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Confirmer le mot de passe">
                            </div>


                        </div>
                        <!-- Fin de la Grille principale -->

                        <div class="mt-4 grid">
                            <button type="submit"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                S'inscrire pour le Chat
                            </button>
                        </div>

                        <div class="text-sm text-gray-400 mt-4">
                            Vous avez déjà un compte? <a href="login.php"
                                class="text-blue-400 hover:text-blue-500">Connectez-vous.</a>
                        </div>
                    </form>


                </div>
                <!-- Fin Carte -->

                <div class="divide-y divide-white">
                    <!-- Bloc d'icône -->
                    <div class="flex gap-x-7 py-6">
                        <svg class="flex-shrink-0 size-6 mt-1.5 text-white" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <path d="M12 17h.01" />
                        </svg>
                        <div class="grow">
                            <h3 class="font-semibold text-white">Sécurité et confidentialité</h3>
                            <p class="mt-1 text-sm text-white">Adoptez des pratiques sécurisées : utilisez des mots de
                                passe robustes et gardez vos communications strictement professionnelles.</p>

                        </div>
                    </div>
                    <!-- Fin Bloc d'icône -->

                    <!-- Bloc d'icône -->
                    <div class="flex gap-x-7 py-6">
                        <svg class="flex-shrink-0 size-6 mt-1.5 text-white" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z" />
                            <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1" />
                        </svg>
                        <div class="grow">
                            <h3 class="font-semibold text-white">Soyez respectueux</h3>
                            <p class="mt-1 text-sm text-white">Respectez toujours vos collègues dans vos communications.
                                Assurez-vous que vos échanges restent professionnels et constructifs pour maintenir un
                                environnement de travail sain et supportif.</p>
                        </div>

                    </div>
                    <!-- Fin Bloc d'icône -->

                    <!-- Bloc d'icône -->
                    <div class="flex gap-x-7 py-6">
                        <svg class="flex-shrink-0 size-6 mt-1.5 text-white" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m7 11 2-2-2-2" />
                            <path d="M11 13h4" />
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                        </svg>
                        <div class="grow">
                            <h3 class="font-semibold text-white">Entraide et collaboration</h3>
                            <p class="mt-1 text-sm text-white">Encouragez l'entraide et utilisez les outils
                                collaboratifs à disposition pour améliorer l'efficacité de l'équipe.</p>

                        </div>
                    </div>
                    <!-- Fin Bloc d'icône -->

                    <!-- Bloc d'icône -->
                    <div class=" flex gap-x-7 py-6">
                        <svg class="flex-shrink-0 size-6 mt-1.5 text-white" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z" />
                            <path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10" />
                        </svg>
                        <div class="grow">
                            <h3 class="font-semibold text-white">Support technique</h3>
                            <p class="mt-1 text-sm text-white">Pour toute assistance technique, veuillez contacter notre
                                équipe de support dédiée.</p>

                        </div>
                    </div>
                    <!-- Fin Bloc d'icône -->
                </div>

            </div>
        </div>
    </div>
    <!-- Fin Contactez-nous -->

</body>

</html>