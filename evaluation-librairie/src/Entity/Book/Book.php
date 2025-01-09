<?php

namespace App\Entity\Book;

class Book
{
    private $id;
    private $title;
    private $author;
    private $description;
    private $datePublication;
    private $isbn;
    private $photoUrl;

    public function __construct(
        $title,
        $author,
        $description,
        $datePublication,
        $isbn,
        $photoUrl,
        $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->description = $description;
        $this->datePublication = $datePublication;
        $this->isbn = $isbn;
        $this->photoUrl = $photoUrl;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getDatePublication()
    {
        return $this->datePublication;
    }
    public function getIsbn()
    {
        return $this->isbn;
    }
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    // Setters
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;
    }
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
    }

    // Méthodes statiques pour interagir avec la base de données

    // Dans la classe Book
    public static function isAvailableForLoan($pdo)
    {
        $query = "SELECT * FROM livres WHERE statut = 'disponible'";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getAllBooks($pdo)
    {
        $query = "SELECT * FROM livres";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getBookById($pdo, $id)
    {
        $query = "SELECT * FROM livres WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function save($pdo)
    {
        if ($this->id) {
            // Mise à jour
            $query =
                "UPDATE livres SET titre = :title, auteur = :author, description = :description, date_publication = :datePublication, isbn = :isbn, photo_url = :photoUrl WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ":title" => $this->title,
                ":author" => $this->author,
                ":description" => $this->description,
                ":datePublication" => $this->datePublication,
                ":isbn" => $this->isbn,
                ":photoUrl" => $this->photoUrl,
                ":id" => $this->id,
            ]);
        } else {
            // Insertion
            $query =
                "INSERT INTO livres (titre, auteur, description, date_publication, isbn, photo_url) VALUES (:title, :author, :description, :datePublication, :isbn, :photoUrl)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ":title" => $this->title,
                ":author" => $this->author,
                ":description" => $this->description,
                ":datePublication" => $this->datePublication,
                ":isbn" => $this->isbn,
                ":photoUrl" => $this->photoUrl,
            ]);
            $this->id = $pdo->lastInsertId();
        }
    }
}
