<?php

namespace banana\Entity;

class Magazine
{
    private int $id;
    private string $name;
    private string $shortDescription;
    private string $photoPath;
    private string $photoName;
    private string $magazineReleaseDate;
    private string $photoUrl;

    public function __construct(
        string $name,
        string $shortDescription,
        string $photoPath,
        string $photoName,
        string $magazineReleaseDate,
        string $photoUrl
    ) {
        $this->name = $name;
        $this->shortDescription = $shortDescription;
        $this->photoPath = $photoPath;
        $this->photoName = $photoName;
        $this->magazineReleaseDate = $magazineReleaseDate;
        $this->photoUrl = $photoUrl;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
    public function getPhotoPath(): string
    {
        return $this->photoPath;
    }
    public function getPhotoName(): string
    {
        return $this->photoName;
    }
    public function getMagazineReleaseDate(): string
    {
        return $this->magazineReleaseDate;
    }
    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function setShortDescription(string $shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }
    public function setPhotoPath(string $photoPath)
    {
        $this->photoPath = $photoPath;
    }

    public function setPhotoName(string $photoName)
    {
        $this->photoName = $photoName;
    }
    public function setMagazineReleaseDate(string $magazineReleaseDate)
    {
        $this->magazineReleaseDate = $magazineReleaseDate;
    }
    public function setPhotoUrl(string $photoUrl)
    {
        $this->photoUrl = $photoUrl;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_description' => $this->shortDescription,
            'photo_path' => $this->photoPath,
            'photo_name' => $this->photoName,
            'magazine_release_date' => $this->magazineReleaseDate,
            'photo_url' => $this->photoUrl,
        ];
    }
}