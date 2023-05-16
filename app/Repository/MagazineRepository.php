<?php

namespace banana\Repository;

use banana\Entity\Magazine;
use PDO;

class MagazineRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Magazine $magazine): void
    {
        $sth = $this->connection->prepare("INSERT INTO magazines (name, short_description, photo_path, photo_name, magazine_release_date, photo_url) VALUES (:name, :short_description, :photo_path, :photo_name, :magazine_release_date, :photo_url) RETURNING id") ;

        $sth->execute([
            'name' => $magazine->getName(),
            'short_description' => $magazine->getShortDescription(),
            'photo_path' => $magazine->getPhotoPath(),
            'photo_name' => $magazine->getPhotoName(),
            'magazine_release_date' => $magazine->getMagazineReleaseDate(),
            'photo_url' => $magazine->getPhotoUrl(),
        ]);

        $data = $sth->fetch();

        $magazine->setId($data['id']);
    }

    public function index(): array
    {
        $sth =  $this->connection->query("SELECT * FROM magazines");

        $magazines = [];

        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {

            $magazines[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'short_description' => $row['short_description'],
                'photo_path' => $row['photo_path'],
                'photo_name' => $row['photo_name'],
                'magazine_release_date' => $row['magazine_release_date'],
                'photo_url' => $row['photo_url']
            ];
        }
        return $magazines;
    }

    public function delete(int $id): string
    {
        $sth = $this->connection->prepare("DELETE FROM magazines WHERE id = :id");

        $sth->bindValue(':id', $id);

        $sth->execute([$id]);

        $sth->fetch();

        return 'Magazine deleted';
    }

    public function setName(string $name, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE magazines SET name = :name WHERE id = :id");

        $sth->bindValue(':name', $name);
        $sth->bindValue(':id', $id);

        $sth->execute();

    }

    public function setShortDescription(string $shortDescription, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE magazines SET short_description = :short_description WHERE id = :id");

        $sth->bindValue(':short_description', $shortDescription);
        $sth->bindValue(':id', $id);

        $sth->execute();
    }

    public function setMagazineReleaseDate(string $magazineReleaseDate, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE magazines SET magazine_release_date = :magazine_release_date WHERE id = :id");

        $sth->bindValue(':magazine_release_date', $magazineReleaseDate);
        $sth->bindValue(':id', $id);

        $sth->execute();
    }

    public function updatePhotoName(string $photoName, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE magazines SET photo_name = :photo_name WHERE id = :id");

        $sth->bindValue(':photo_name', $photoName);
        $sth->bindValue(':id', $id);

        $sth->execute();
    }

    public function updatePhotoPath(string $photoPath, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE magazines SET photo_path = :photo_path WHERE id = :id");

        $sth->bindValue(':photo_path', $photoPath);
        $sth->bindValue(':id', $id);

        $sth->execute();
    }

    public function updatePhotoUrl(string $photoUrl, int $id): void
    {
        $sth = $this->connection->prepare("UPDATE magazines SET photo_url = :photo_url WHERE id = :id");

        $sth->bindValue(':photo_url', $photoUrl);
        $sth->bindValue(':id', $id);

        $sth->execute();
    }

        public function findMagazine(int $id)
    {
        $sth = $this->connection->prepare('SELECT * FROM magazines WHERE id = :id');

        $sth->bindValue(':id', $id);

        $sth->execute();

        $data = $sth->fetch();

        $magazine = new Magazine(
            $data['name'],
            $data['short_description'],
            $data['photo_path'],
            $data['photo_name'],
            $data['magazine_release_date'],
            $data['photo_url']
        );

        $magazine->setId($data['id']);

        return $magazine;
    }

    public function deletePhotoName(int $id)
    {
        $sth = $this->connection->prepare("DELETE photo_name FROM magazines WHERE id = :id");

        $sth->bindValue(':id', $id);

        $sth->execute();

        $sth->fetch();

        return 'Photo name deleted';
    }

    public function deletePhotoPath(int $id)
    {
        $sth = $this->connection->prepare("DELETE photo_path FROM magazines WHERE id = :id");

        $sth->bindValue(':id', $id);

        $sth->execute();

        $sth->fetch();

        return 'Photo path deleted';
    }

    public function deletePhotoUrl(int $id)
    {
        $sth = $this->connection->prepare("DELETE photo_url FROM magazines WHERE id = :id");

        $sth->bindValue(':id', $id);

        $sth->execute();

        $sth->fetch();

        return 'Photo url deleted';
    }
}