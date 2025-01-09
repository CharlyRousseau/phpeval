<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Database\DatabaseConnection;

$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

// Récupérer le nombre total de livres
$queryTotalBooks = "SELECT COUNT(*) as total_books FROM livres";
$stmtTotalBooks = $pdo->prepare($queryTotalBooks);
$stmtTotalBooks->execute();
$resultTotalBooks = $stmtTotalBooks->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre d'utilisateurs enregistrés
$queryTotalUsers = "SELECT COUNT(*) as total_users FROM utilisateurs";
$stmtTotalUsers = $pdo->prepare($queryTotalUsers);
$stmtTotalUsers->execute();
$resultTotalUsers = $stmtTotalUsers->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Librairie XYZ</title>

    <!-- DaisyUI + Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<header>
    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-lg">
        <div class="flex-1">
            <a href="index.php" class="btn btn-ghost text-xl">Librairie XYZ</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="books/index.php">Voir les livres</a></li>
                <?php if (isset($_SESSION["user"])): ?>
                    <li><a href="profile/profile.php">Mon profil</a></li>
                    <li><a href="logout.php">Deconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>

<div class="wrapper">
    <!-- Dashboard Stats -->
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Statistiques</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="stat p-6 bg-white rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold">Total des Livres</h3>
                <p class="text-2xl text-gray-700"><?= $resultTotalBooks[
                    "total_books"
                ] ?></p>
            </div>
            <div class="stat p-6 bg-white rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold">Utilisateurs Enregistrés</h3>
                <p class="text-2xl text-gray-700"><?= $resultTotalUsers[
                    "total_users"
                ] ?></p>
            </div>
            <!-- Add more statistics as necessary -->
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-4 mt-8">
    <div class="container text-center">
        <p>&copy; <?= date("Y") ?> Librairie XYZ. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>
