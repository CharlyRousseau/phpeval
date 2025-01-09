Feature: Book management

  Scenario: Adding a new book to the database
    Given I am connected to the database
    When I add a book with the following details:
      | title                | author            | description             | date_publication | isbn           | photo_url                |
      | The Great Gatsby     | F. Scott Fitzgerald | A novel set in the 1920s. | 1925-04-10       | 9780743273565 | http://example.com/photo.jpg |
    Then the book should be added to the database
