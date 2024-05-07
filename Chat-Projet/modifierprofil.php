<?php
session_start();
include ("connexion_bdd.php"); // Assurez-vous d'inclure votre script de connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations actuelles de l'utilisateur
$result = mysqli_query($con, "SELECT first_name, last_name, profile_photo FROM utilisateur WHERE idutilisateur = '$user_id'");
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Gestion de la photo de profil
    if (!empty($_FILES['profile_photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file);
    } else {
        $target_file = $user['profile_photo']; // Conserver l'ancienne photo si aucune n'est chargée
    }

    // Mettre à jour la base de données
    $update_query = "UPDATE utilisateur SET first_name = '$first_name', last_name = '$last_name', profile_photo = '$target_file'";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password = '$hashed_password'";
    }

    $update_query .= " WHERE idutilisateur = '$user_id'";
    mysqli_query($con, $update_query);

    header("location: login.php"); // Rediriger vers la page de profil
    exit;
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white font-sans flex items-center justify-center min-h-screen">
    <div class="max-w-md mx-auto p-6 bg-gray-800 rounded-lg shadow-lg">
        <h1 class="text-xl mb-6 text-center">Modifier votre profil</h1>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="profile_photo" class="block text-sm text-gray-400">Photo de profil:</label>
                <input type="file" id="profile_photo" name="profile_photo"
                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <div>
                <label for="first_name" class="block text-sm text-gray-400">Prénom:</label>
                <input type="text" id="first_name" name="first_name" value="<?= $user['first_name'] ?>" required
                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="last_name" class="block text-sm text-gray-400">Nom:</label>
                <input type="text" id="last_name" name="last_name" value="<?= $user['last_name'] ?>" required
                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500">
            </div>

        
            <div>
                <label for="password" class="block text-sm text-gray-400">Nouveau mot de passe (laisser vide si
                    inchangé):</label>
                <input type="password" id="password" name="password"
                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <input type="submit" name="submit" value="Mettre à jour"
                class="mt-4 w-full py-3 px-6 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold cursor-pointer">
        </form>
    </div>
</body>

</html>