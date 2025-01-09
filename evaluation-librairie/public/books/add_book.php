<?php
require __DIR__ . "/../../vendor/autoload.php";

use App\Database\DatabaseConnection;
use App\Entity\Book\Book;

session_start();

// Vérifier si l'utilisateur est admin
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
if (!$isAdmin) {
    // Si l'utilisateur n'est pas admin, rediriger vers la page principale
    header("Location: index.php");
    exit();
}

// Connexion à la base de données
$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

// Traitement du formulaire d'ajout
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST["titre"];
    $auteur = $_POST["auteur"];
    $description = $_POST["description"];
    $date_publication = $_POST["date_publication"];
    $isbn = $_POST["isbn"];
    $photo_url = $_POST["photo_url"];

    // Créer une instance de Book
    $book = new Book(
        $titre,
        $auteur,
        $description,
        $date_publication,
        $isbn,
        $photo_url
    );

    // Sauvegarder le livre dans la base de données
    $book->save($pdo);

    // Rediriger après l'ajout
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css');
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-4xl font-bold text-center my-6">Ajouter un Nouveau Livre</h1>

        <form action="add_book.php" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="titre" class="block text-gray-700">Titre</label>
                <input type="text" name="titre" id="titre" required class="input input-bordered w-full mt-2">
            </div>
            <div class="mb-4">
                <label for="auteur" class="block text-gray-700">Auteur</label>
                <input type="text" name="auteur" id="auteur" required class="input input-bordered w-full mt-2">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" required class="textarea textarea-bordered w-full mt-2" rows="4"></textarea>
            </div>
            <div class="mb-4">
                <label for="date_publication" class="block text-gray-700">Date de Publication</label>
                <input type="date" name="date_publication" id="date_publication" required class="input input-bordered w-full mt-2">
            </div>
            <div class="mb-4">
                <label for="isbn" class="block text-gray-700">ISBN</label>
                <input type="text" name="isbn" id="isbn" required class="input input-bordered w-full mt-2">
            </div>
            <div class="mb-4">
                <label for="photo_url" class="block text-gray-700">URL de l'image</label>
                <input type="text" name="photo_url" id="photo_url" required class="input input-bordered w-full mt-2">
            </div>
            <div class="text-center mt-6">
                <button type="submit" class="btn btn-primary">Ajouter le Livre</button>
            </div>
        </form>
    </div>
</body>
</html>
