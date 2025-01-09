<?php
require __DIR__ . "/../../vendor/autoload.php";

use App\Entity\Emprunt\Emprunt;
use App\Database\DatabaseConnection;

session_start();

// Vérifier si l'utilisateur est connecté
$userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

if (!$userId || !isset($_POST["book_id"])) {
    header("Location: index.php");
    exit();
}

$bookId = $_POST["book_id"];

// Créer un emprunt
$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

$emprunt = new Emprunt($userId, $bookId);
$emprunt->enregistrer($pdo);

// Rediriger vers la liste des livres
header("Location: index.php");
exit();
