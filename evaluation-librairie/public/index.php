<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Database\DatabaseConnection;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Utilisation de la classe DatabaseConnection pour accéder à la base de données
$db = DatabaseConnection::getInstance();
$pdo = $db->getConnection();

$page = isset($_GET["page"]) ? $_GET["page"] : "home";

// Vérifier si l'utilisateur est admin
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";

if ($page === "login") {
    echo "Avant inclusion du login.php"; // Débogage
    include "login.php";
    echo "Après inclusion du login.php"; // Débogage
} elseif ($page === "books") {
    include "books.php";
} elseif ($page === "add_book" && $isAdmin) {
    include "add_book.php"; // Ajout d'un lien pour l'ajout d'un livre si admin
} else {
    include "home.php";
}
?>
