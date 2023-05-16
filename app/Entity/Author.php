<?php

namespace banana\Entity;

class Author
{
    private int $id;
    private string $lastName;
    private string $firstName;
    private string $surname;

    public function __construct(
        string $lastName,
        string $firstName,
        string $surname
    ) {
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->surname = $surname;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'surname' => $this->surname
        ];
    }
}