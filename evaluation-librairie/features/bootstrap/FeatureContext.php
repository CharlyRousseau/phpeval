<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use App\Database\DatabaseConnection;
use PDO;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;

class FeatureContext implements Context
{
    private ?PDO $connection;
    private string $title;
    private string $author;
    private string $isbn;

    public function __construct()
    {
        // Initialize the connection using the DatabaseConnection singleton
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    #[Given("I am connected to the database")]
    public function iAmConnectedToTheDatabase()
    {
        // Optionally, you can check if the connection is established.
        if (!$this->connection) {
            throw new \Exception("Database connection failed");
        }
    }

    #[When("I add a book with the following details:")]
    public function iAddABook(TableNode $table)
    {
        // Iterate over each row in the table
        foreach ($table as $row) {
            // Accessing book details from the table
            $this->title = $row["title"];
            $this->author = $row["author"];
            $description = $row["description"];
            $datePublication = $row["date_publication"];
            $this->isbn = $row["isbn"];
            $photoUrl = $row["photo_url"];

            // Insert the book into the database using the connection from DatabaseConnection
            $query = "INSERT INTO livres (titre, auteur, description, date_publication, isbn, photo_url)
                      VALUES (:title, :author, :description, :datePublication, :isbn, :photoUrl)";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([
                ":title" => $this->title,
                ":author" => $this->author,
                ":description" => $description,
                ":datePublication" => $datePublication,
                ":isbn" => $this->isbn,
                ":photoUrl" => $photoUrl,
            ]);
        }
    }

    #[Then("the book should be added to the database")]
    public function theBookShouldBeAddedToTheDatabase()
    {
        // Verify the book was added to the database
        $query =
            "SELECT * FROM livres WHERE titre = :title AND auteur = :author AND isbn = :isbn";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ":title" => $this->title,
            ":author" => $this->author,
            ":isbn" => $this->isbn,
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new \Exception("Book not found in the database");
        }
    }
}
