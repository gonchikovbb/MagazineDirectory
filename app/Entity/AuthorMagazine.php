<?php

namespace banana\Entity;

class AuthorMagazine
{
    private int $id;
    private int $magazineId;
    private int $authorId;

    public function __construct(
        int $magazineId,
        int $authorId
    ) {
        $this->magazineId = $magazineId;
        $this->authorId = $authorId;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMagazineId(): int
    {
        return $this->magazineId;
    }
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setMagazineId(int $magazineId)
    {
        $this->magazineId = $magazineId;
    }
    public function setAuthorId(int $authorId)
    {
        $this->authorId = $authorId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            '$magazin_id' => $this->magazineId,
            '$author_id' => $this->authorId,
        ];
    }
}