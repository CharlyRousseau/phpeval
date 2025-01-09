<?php
require "../../vendor/autoload.php";
use App\Database\DatabaseConnection;
use App\Entity\User\User;

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION["user_id"];

// Get the database connection
$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

// Retrieve user information using the User entity
$user = User::getUserById($user_id, $pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Profil</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DaisyUI CDN -->
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

<header>
    <h1 class="text-3xl text-center mt-8">Modifier votre Profil</h1>
</header>

<div class="max-w-md mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg">
    <form method="post" action="update_profile.php">
        <label for="new_name" class="block text-lg mb-2">Nouveau Nom :</label>
        <input type="text" name="new_name" value="<?php echo htmlspecialchars(
            $user->getNom()
        ); ?>" required class="input input-bordered w-full mb-4">

        <label for="new_email" class="block text-lg mb-2">Nouvel Email :</label>
        <input type="email" name="new_email" value="<?php echo htmlspecialchars(
            $user->getEmail()
        ); ?>" required class="input input-bordered w-full mb-4">

        <button type="submit" class="btn btn-primary w-full">Enregistrer les Modifications</button>
    </form>

    <div class="mt-4">
        <button onclick="window.location.href ='profile.php'" class="btn btn-ghost w-full">Retour au Profil</button>
        <button onclick="window.location.href ='index.php'" class="btn btn-ghost w-full mt-2">Retour à l'accueil</button>
    </div>
</div>

</body>
</html>
