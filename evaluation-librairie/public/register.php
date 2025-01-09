<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Database\DatabaseConnection;

$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Vérification de l'existence de l'email
    $query = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([":email" => $email]);

    if ($stmt->rowCount() > 0) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertion de l'utilisateur dans la base de données avec le mot de passe hashé
        $query =
            "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:name, :prenom, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ":name" => $name,
            ":prenom" => $prenom,
            ":email" => $email,
            ":password" => $hashedPassword,
        ]);

        if ($stmt) {
            // Redirection vers la page de connexion
            header("Location: login.php");
            exit();
        } else {
            $error = "Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Librairie XYZ</title>

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

<!-- Inscription Section -->
<div class="hero min-h-screen bg-cover bg-center" style="background-image: url('https://source.unsplash.com/1600x900/?library');">
    <div class="hero-overlay bg-opacity-60"></div>
    <div class="hero-content text-center text-white">
        <div class="max-w-md">
            <h2 class="text-3xl font-bold mb-4">Créer un compte</h2>

            <!-- Registration Form -->
            <form method="post" action="" class="bg-white p-8 rounded-lg shadow-lg max-w-sm mx-auto">
                <div class="mb-4">
                    <input type="text" name="name" placeholder="Nom" required class="input input-bordered w-full" />
                </div>
                <div class="mb-4">
                    <input type="text" name="prenom" placeholder="Prénom" required class="input input-bordered w-full" />
                </div>
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email" required class="input input-bordered w-full" />
                </div>
                <div class="mb-6">
                    <input type="password" name="password" placeholder="Mot de passe" required class="input input-bordered w-full" />
                </div>
                <button type="submit" class="btn btn-primary w-full">S'inscrire</button>
            </form>

            <!-- Error Message -->
            <?php if (isset($error)): ?>
                <div class="mt-4 text-red-500 text-center"><?= $error ?></div>
            <?php endif; ?>

            <!-- Login Link -->
            <p class="mt-4">Vous avez déjà un compte ? <a href="login.php" class="link text-white">Connectez-vous ici</a>.</p>
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
