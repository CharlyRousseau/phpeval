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
    <title>Mon profil - Librairie XYZ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<header>
    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-lg">
        <div class="flex-1">
            <a href="../index.php" class="btn btn-ghost text-xl">Librairie XYZ</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../books/books.php">Voir les livres</a></li>
                <?php if (isset($_SESSION["user"])): ?>
                    <li><a href="profile.php">Mon profil</a></li>
                    <li><a href="../logout.php">Deconnexion</a></li>
                <?php else: ?>
                    <li><a href="../login.php">Connexion</a></li>
                    <li><a href="../register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>

<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-semibold text-center mb-6">Mon Profil</h1>

    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
        <p class="text-lg">Nom : <?php echo htmlspecialchars(
            $user->getNom()
        ); ?></p>
        <p class="text-lg">Email : <?php echo htmlspecialchars(
            $user->getEmail()
        ); ?></p>

        <!-- Edit Profile Button -->
        <div class="mt-4">
            <a href="edit_profile.php" class="btn btn-primary">Modifier le Profil</a>
        </div>

        <!-- Back to Home Button -->
        <div class="mt-4">
            <a href="../index.php" class="btn btn-ghost">Retour à l'accueil</a>
        </div>
    </div>
</div>

<footer class="bg-gray-900 text-white py-4 mt-8">
    <div class="container text-center">
        <p>&copy; <?= date("Y") ?> Librairie XYZ. Tous droits réservés.</p>
    </div>
</footer>

</body>
</html>
