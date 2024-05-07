<?php
session_start(); // Démarrer la session
if (!empty($_SESSION['user_email'])) { // Vérifier si l'utilisateur est déjà connecté
    header('Location: chat.php'); // Redirection vers chat si déjà connecté
    exit;
}

$error = ''; // Initialiser la variable d'erreur

// Vérifier si le formulaire a été soumis via POST et si les champs email et mot de passe sont présents
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['password'])) {
    include "connexion_bdd.php"; // Inclure le script de connexion à la base de données

    // Sécuriser l'entrée en échappant les caractères spéciaux pour prévenir les injections SQL
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];

    // Bloc de code pour gérer la connexion spécifique de l'administrateur
    if ($email == "admin@admin" && $password == "admin12345678910") {
        $_SESSION['user_email'] = $email; // Enregistrer l'email dans la session
        $_SESSION['is_admin'] = true; // Marquer la session comme celle d'un administrateur
        header("Location: confirmation_admin.php"); // Rediriger vers la page de confirmation d'admin
        exit;
    }

    // Vérifier si les champs email et mot de passe ne sont pas vides
    if (!empty($email) && !empty($password)) {
        // Préparer une requête SQL pour rechercher l'utilisateur par email
        $stmt = $con->prepare("SELECT idutilisateur, password FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email); // Sécuriser la variable email utilisée dans la requête SQL
        $stmt->execute();
        $result = $stmt->get_result();

        // Traiter le résultat de la requête
        if ($user = $result->fetch_assoc()) {
            // Vérifier si le mot de passe fourni correspond au hachage stocké dans la base de données
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_email'] = $email; // Stocker l'email de l'utilisateur dans la session
                $_SESSION['user_id'] = $user['idutilisateur']; // Stocker l'ID de l'utilisateur dans la session
                header("Location: chat.php"); // Rediriger l'utilisateur vers la page de chat
                exit;
            } else {
                $error = "Email ou mot de passe incorrect(s) !";
            }
        } else {
            $error = "Email ou mot de passe incorrect(s) !";
        }
    } else {
        $error = "Veuillez remplir tous les champs !";
    }
}
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


<body class="bg-gray-900 flex flex-col items-center justify-center h-screen">


    <div class="text-white text-3xl font-bold mb-10">CollabChat</div>
    <div class="w-96 bg-gray-800 p-8 rounded-lg shadow-lg">
        <div class="mb-6">
            <h2 class="text-white text-lg font-semibold mb-4">Connecte-toi pour aller dans le Chat!</h2>
            <?php if (!empty($error)): ?>
                <div class="text-red-500"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-sm text-gray-400">Email</label>
                    <input type="email" id="email" name="email"
                        class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm text-gray-400">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••••"
                        class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded">
                    Connexion
                </button>
                <div class="text-sm text-gray-400 mt-4">
                    Vous n'avez pas de compte? <a href="inscription.php" class="text-blue-400 hover:text-blue-500">Crée
                        un compte!</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>