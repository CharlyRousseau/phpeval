<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Database\DatabaseConnection;

$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Requête pour récupérer l'utilisateur par son email
    $query =
        "SELECT id, mot_de_passe, prenom, role FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["mot_de_passe"])) {
        // Mot de passe correct, connecter l'utilisateur
        session_start();
        $_SESSION["user"] = $email;
        $_SESSION["prenom"] = $user["prenom"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["user_id"] = $user["id"];
        header("Location: index.php"); // Rediriger vers la page d'accueil
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Librairie XYZ</title>

    <!-- DaisyUI + Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-lg">
        <div class="flex-1">
            <a href="index.php" class="btn btn-ghost text-xl">Librairie XYZ</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="books.php">Voir les livres</a></li>
                <li><a href="login.php">Connexion</a></li>
            </ul>
        </div>
    </div>
<!-- Login Section -->
<div class="hero min-h-screen bg-cover bg-center" style="background-image: url('https://source.unsplash.com/1600x900/?library');">
    <div class="hero-overlay bg-opacity-60"></div>
    <div class="hero-content text-center text-white">
        <div class="max-w-md">
            <h2 class="text-3xl font-bold mb-4">Connexion à votre compte</h2>

            <!-- Login Form -->
            <form method="post" action="" class="bg-white p-8 rounded-lg shadow-lg max-w-sm mx-auto">
                <div class="mb-4">
                    <input type="text" name="email" placeholder="Email" required class="input input-bordered w-full"/>
                </div>
                <div class="mb-6">
                    <input type="password" name="password" placeholder="Mot de passe" required class="input input-bordered w-full"/>
                </div>
                <button type="submit" class="btn btn-primary w-full">Se connecter</button>
            </form>

            <!-- Register Link -->
            <p class="mt-4">Vous n'avez pas de compte ? <a href="register.php" class="link text-white">S'inscrire</a></p>

            <!-- Error Message -->
            <?php if (isset($error)): ?>
                <div class="mt-4 text-red-500 text-center"><?= $error ?></div>
            <?php endif; ?>
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
