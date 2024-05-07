<?php
session_start();
// Vérifier si l'utilisateur est l'administrateur et s'il est connecté
if (empty($_SESSION['is_admin']) || $_SESSION['user_email'] != "admin@admin") {
    header('Location: login.php'); // Redirection vers login si l'utilisateur n'est pas l'admin
    exit;
}

$error = ''; // Initialiser la variable d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_code'])) {
    $adminCode = $_POST['admin_code'];
    // Vérifier si le code saisi est correct
    if ($adminCode == "2425268485867913") {
        header("Location: admin.php"); // Redirection vers la page d'administration
        exit;
    } else {
        $error = "Code incorrect !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css">
</head>

<body class="bg-gray-900 flex flex-col items-center justify-center h-screen">
    <div class="w-96 bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-white text-lg font-semibold mb-4">Veuillez entrer le code d'administration</h2>
        <?php if (!empty($error)): ?>
            <div class="text-red-500"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-6">
                <label for="admin_code" class="block text-sm text-gray-400">Code Administrateur</label>
                <input type="text" id="admin_code" name="admin_code"
                    class="w-full p-3 bg-gray-700 rounded text-white outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">
                Confirmer
            </button>
        </form>
    </div>
</body>

</html>