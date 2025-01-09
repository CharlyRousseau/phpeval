<?php
require __DIR__ . "/../../vendor/autoload.php";

use App\Entity\Emprunt\Emprunt;
use App\Database\DatabaseConnection;

session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET["id"])) {
    $bookId = intval($_GET["id"]);
    $userId = $_SESSION["user_id"]; // Assurez-vous que l'utilisateur est connecté

    // Connexion à la base de données
    $db = DatabaseConnection::getInstance();
    $pdo = $db->getConnection();

    // Récupérer les détails du livre
    $query = "SELECT * FROM livres WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([":id" => $bookId]);

    if ($stmt->rowCount() == 1) {
        $book = $stmt->fetch();

        // Vérification si le livre est emprunté par l'utilisateur
        $emprunt = Emprunt::estLivreEmprunte($pdo, $bookId);
        if ($emprunt && $emprunt["id_utilisateur"] == $userId) {
            $isBorrowed = true;
        } else {
            $isBorrowed = false;
        }
    } else {
        echo "Livre non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="container mx-auto p-6">
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-center">Détails du Livre</h1>
        </header>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <?php if (isset($book)): ?>
                <h2 class="text-2xl font-bold"><?= htmlspecialchars(
                    $book["titre"]
                ) ?></h2>
                <p><strong>Auteur:</strong> <?= htmlspecialchars(
                    $book["auteur"]
                ) ?></p>
                <p><strong>Description:</strong> <?= nl2br(
                    htmlspecialchars($book["description"])
                ) ?></p>

                <!-- Bouton "Rendre" si le livre est emprunté -->
                <?php if ($isBorrowed): ?>
                    <button onclick="window.location.href = 'retour.php?id=<?= $bookId ?>'" class="btn btn-success mt-4">Rendre</button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
