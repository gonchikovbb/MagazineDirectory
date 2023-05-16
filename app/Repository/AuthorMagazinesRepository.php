<?php

namespace banana\Repository;

use banana\Entity\AuthorMagazine;
use PDO;

class AuthorMagazinesRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(array $authorMagazines): void
    {
        foreach ($authorMagazines as $authorMagazine) {
            $sth = $this->connection->prepare("INSERT INTO author_magazines (magazine_id, author_id) VALUES (:magazine_id, :author_id) RETURNING id") ;

            $sth->execute([
                'magazine_id' => $authorMagazine->getMagazineId(),
                'author_id' => $authorMagazine->getAuthorId()
            ]);

            $data = $sth->fetch();

            $authorMagazine->setId($data['id']);
        }
    }

    public function index(): array
    {
        $sth =  $this->connection->query("SELECT * FROM author_magazines");

        $authorMagazine = [];

        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {

            $authorMagazine[] = [
                'id' => $row['id'],
                'magazine_id' => $row['magazine_id'],
                'author_id' => $row['author_id'],
            ];
        }
        return $authorMagazine;
    }

    public function delete(int $id): string
    {
        $sth = $this->connection->prepare("DELETE FROM author_magazines WHERE id = :id");

        $sth->bindValue(':id', $id);

        $sth->execute([$id]);

        $sth->fetch();

        return 'Author Magazine deleted';
    }

    public function setMagazine(int $magazineId, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE author_magazines SET magazine_id = :magazine_id WHERE id = :id");

        $sth->bindValue(':magazine_id', $magazineId);
        $sth->bindValue(':id', $id);

        $sth->execute();

    }

    public function setAuthor(int $authorId, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE author_magazines SET author_id = :author_id WHERE id = :id");

        $sth->bindValue(':author_id', $authorId);
        $sth->bindValue(':id', $id);

        $sth->execute();

    }

        public function findAuthorsMagazine(int $magazineId)
    {
        $sth = $this->connection->prepare('SELECT author_id FROM author_magazines WHERE magazine_id = :magazine_id ORDER BY author_id ASC');

        $sth->bindValue(':magazine_id', $magazineId);

        $sth->execute();

        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {

            $authorIds[] = [
                'author_id' => $row['author_id'],
            ];
        }

        return $authorIds;
    }

    public function deleteAuthorsMagazine(int $magazineId)
    {
        $sth = $this->connection->prepare("DELETE FROM author_magazines WHERE magazine_id = :magazine_id");

        $sth->bindValue(':magazine_id', $magazineId);

        $sth->execute();

        $sth->fetch();

        return 'Author deleted';
    }
}