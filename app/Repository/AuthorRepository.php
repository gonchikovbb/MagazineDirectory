<?php

namespace banana\Repository;

use banana\Entity\Author;
use PDO;

class AuthorRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Author $author): void
    {
        $sth = $this->connection->prepare("INSERT INTO authors (last_name, first_name, surname) VALUES (:last_name, :first_name, :surname) RETURNING id") ;

        $sth->execute([
            'last_name' => $author->getLastName(),
            'first_name' => $author->getFirstName(),
            'surname' => $author->getSurname()
        ]);

        $data = $sth->fetch();

        $author->setId($data['id']);
    }

    public function index(): array
    {
        $sth =  $this->connection->query("SELECT * FROM authors");

        $authors = [];

        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {

            $authors[] = [
              'id' => $row['id'],
              'last_name' => $row['last_name'],
              'first_name' => $row['first_name'],
              'surname' => $row['surname']
            ];
        }
        return $authors;
    }

    public function delete(int $id): string
    {
        $sth = $this->connection->prepare("DELETE FROM authors WHERE id = :id");

        $sth->bindValue(':id', $id);

        $sth->execute([$id]);

        $sth->fetch();

        return 'Author deleted';
    }

    public function setLastName(string $lastName, int $id): void
    {
        $sth = $this->connection->prepare('UPDATE authors SET last_name = :last_name WHERE id = :id');

        $sth->bindValue(':last_name', $lastName);
        $sth->bindValue(':id', $id);

        $sth->execute();
    }


    public function setFirstName(string $firstName, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE authors SET first_name = :first_name WHERE id = :id");

        $sth->bindValue(':first_name', $firstName);
        $sth->bindValue(':id', $id);

        $sth->execute();

    }

    public function setSurname(string $surname, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE authors SET surname = :surname WHERE id = :id");

        $sth->bindValue(':surname', $surname);
        $sth->bindValue(':id', $id);

        $sth->execute();

    }

        public function findAuthor(int $id)
    {
        $sth = $this->connection->prepare('SELECT * FROM authors WHERE id = :id');

        $sth->bindValue(':id', $id);

        $sth->execute();

        $data = $sth->fetch();

        $author = new Author(
            $data['last_name'],
            $data['first_name'],
            $data['surname'],
        );

        $author->setId($data['id']);

        return $author;
    }
}