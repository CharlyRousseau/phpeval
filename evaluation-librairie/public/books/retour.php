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

    $db = DatabaseConnection::getInstance();
    $pdo = $db->getConnection();

    // Vérifier si le livre est emprunté par l'utilisateur
    $query =
        "SELECT * FROM emprunts WHERE id_livre = :id_livre AND id_utilisateur = :id_utilisateur AND statut = 'en cours'";
    $stmt = $pdo->prepare($query);
    $stmt->execute([":id_livre" => $bookId, ":id_utilisateur" => $userId]);

    if ($stmt->rowCount() > 0) {
        $empruntData = $stmt->fetch();

        // Créer l'objet Emprunt avec l'ID récupéré
        $emprunt = new Emprunt(
            $empruntData["id_utilisateur"],
            $empruntData["id_livre"]
        );

        // Initialiser l'ID de l'emprunt
        $emprunt->setId($empruntData["id"]);

        // Mettre à jour l'emprunt avec la date de retour
        $emprunt->retourner($pdo);

        // Message de succès
        echo "Livre rendu avec succès.";
    } else {
        echo "Aucun emprunt en cours pour ce livre.";
    }
} else {
    echo "ID de livre non spécifié.";
}
?>
