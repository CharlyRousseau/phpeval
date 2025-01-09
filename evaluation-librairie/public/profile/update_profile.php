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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST["new_name"];
    $newEmail = $_POST["new_email"];

    // Update the user's profile
    $user->updateProfile($newName, $newEmail, $pdo);

    // Redirect to the profile page after the update
    header("Location: profile.php");
    exit();
}
?>
