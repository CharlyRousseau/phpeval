<?php
use PHPUnit\Framework\TestCase;
use App\Entity\Book\Book;

class BookTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Mock PDO instance for testing
        $this->pdo = $this->createMock(\PDO::class);
    }

    public function testBookConstructorAndGetters()
    {
        $book = new Book(
            "Title",
            "Author",
            "Description of the book",
            "2025-01-01",
            "1234567890",
            "http://example.com/photo.jpg"
        );

        $this->assertEquals("Title", $book->getTitle());
        $this->assertEquals("Author", $book->getAuthor());
        $this->assertEquals("Description of the book", $book->getDescription());
        $this->assertEquals("2025-01-01", $book->getDatePublication());
        $this->assertEquals("1234567890", $book->getIsbn());
        $this->assertEquals(
            "http://example.com/photo.jpg",
            $book->getPhotoUrl()
        );
    }

    public function testSaveNewBook()
    {
        $book = new Book(
            "New Book",
            "New Author",
            "New Description",
            "2025-01-01",
            "0987654321",
            "http://example.com/photo2.jpg"
        );

        $this->pdo
            ->expects($this->once())
            ->method("prepare")
            ->with(
                "INSERT INTO livres (titre, auteur, description, date_publication, isbn, photo_url) VALUES (:title, :author, :description, :datePublication, :isbn, :photoUrl)" // Adjusted expected query
            )
            ->willReturn($this->createMock(\PDOStatement::class));

        $book->save($this->pdo);
    }

    public function testIsAvailableForLoan()
    {
        $this->pdo
            ->expects($this->once())
            ->method("query")
            ->with("SELECT * FROM livres WHERE statut = 'disponible'")
            ->willReturn($this->createMock(\PDOStatement::class));

        Book::isAvailableForLoan($this->pdo);
    }

    public function testGetAllBooks()
    {
        $this->pdo
            ->expects($this->once())
            ->method("query")
            ->with("SELECT * FROM livres")
            ->willReturn($this->createMock(\PDOStatement::class));

        Book::getAllBooks($this->pdo);
    }

    public function testGetBookById()
    {
        $this->pdo
            ->expects($this->once())
            ->method("prepare")
            ->with("SELECT * FROM livres WHERE id = :id")
            ->willReturn($this->createMock(\PDOStatement::class));

        Book::getBookById($this->pdo, 1);
    }
}
