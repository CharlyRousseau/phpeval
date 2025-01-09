<?php
namespace App\Entity\User;
use App\Entity\Emprunt\Emprunt;

class User
{
    private $id;
    private $nom;
    private $email;

    // Constructor
    public function __construct($id, $nom, $email)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
    }

    // Getters and Setters for each property
    public function getId()
    {
        return $this->id;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    // Method to retrieve user information from the database by user ID
    public static function getUserById($user_id, $pdo)
    {
        $query = "SELECT * FROM utilisateurs WHERE id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([":user_id" => $user_id]);
        $userInfo = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($userInfo) {
            return new self(
                $userInfo["id"],
                $userInfo["nom"],
                $userInfo["email"]
            );
        }
        return null;
    }

    // Method to update user details in the database
    public function updateProfile($newNom, $newEmail, $pdo)
    {
        // Update user properties
        $this->setNom($newNom);
        $this->setEmail($newEmail);

        // Update user information in the database
        $query =
            "UPDATE utilisateurs SET nom = :nom, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ":nom" => $this->getNom(),
            ":email" => $this->getEmail(),
            ":id" => $this->getId(),
        ]);
    }

    // Méthode pour obtenir l'historique des emprunts d'un utilisateur
    public function getEmprunts($pdo)
    {
        return Emprunt::getHistorique($pdo, $this->id);
    }
}
?>
