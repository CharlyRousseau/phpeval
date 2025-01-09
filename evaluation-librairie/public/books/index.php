<?php
require __DIR__ . "/../../vendor/autoload.php";

use App\Entity\Book\Book;
use App\Entity\Emprunt\Emprunt;
use App\Database\DatabaseConnection;

$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

$books = Book::getAllBooks($pdo);

// Vérifier si l'utilisateur est admin ou non
session_start();
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";

// Vérifier si l'utilisateur est connecté
$userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Livres</title>
    <!-- Daisy UI via Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css');
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-4xl font-bold text-center my-6">Liste des Livres</h1>

        <!-- Afficher le bouton "Ajouter un Livre" si admin -->
        <?php if ($isAdmin): ?>
            <div class="text-right mb-6">
                <a href="add_book.php" class="btn btn-primary">Ajouter un Livre</a>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($books as $book): ?>
                <div class="card bg-white shadow-xl p-4">
                    <img src="<?= htmlspecialchars(
                        $book["photo_url"]
                    ) ?>" alt="Image de <?= htmlspecialchars(
    $book["titre"]
) ?>" class="rounded-lg mb-4">
                    <h2 class="text-xl font-bold">
                        <a href="book_details.php?id=<?= $book[
                            "id"
                        ] ?>" class="text-blue-500 hover:underline"><?= htmlspecialchars(
    $book["titre"]
) ?></a>
                    </h2>
                    <p class="text-gray-700">Auteur : <?= htmlspecialchars(
                        $book["auteur"]
                    ) ?></p>
                    <p class="text-gray-600">Publié le : <?= htmlspecialchars(
                        $book["date_publication"]
                    ) ?></p>
                    <p class="text-gray-600">ISBN : <?= htmlspecialchars(
                        $book["isbn"]
                    ) ?></p>
                    <p class="text-gray-700 mt-2"><?= nl2br(
                        htmlspecialchars($book["description"])
                    ) ?></p>
                    <div class="mt-4">
                        <?php
                        // Vérifier si le livre est déjà emprunté
                        $emprunt = Emprunt::estLivreEmprunte($pdo, $book["id"]);
                        if (!$emprunt): ?>
                            <span class="badge badge-success">
                                Disponible
                            </span>
                        <?php else: ?>
                            <span class="badge badge-error">
                                Emprunté
                            </span>
                        <?php endif;
                        ?>
                    </div>

                    <?php if ($userId): ?>
                        <?php if (!$emprunt): ?>
                            <form action="emprunter.php" method="POST">
                                <input type="hidden" name="book_id" value="<?= $book[
                                    "id"
                                ] ?>">
                                <button type="submit" class="btn btn-primary mt-4">Emprunter</button>
                            </form>
                        <?php else: ?>
                            <p class="text-red-500 mt-4">Ce livre est déjà emprunté.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-gray-500 mt-4">Vous devez être connecté pour emprunter ce livre.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
