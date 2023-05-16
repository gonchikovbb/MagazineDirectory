<?php

namespace banana\Service;

use banana\Entity\AuthorMagazine;
use banana\Repository\AuthorMagazinesRepository;
use banana\Repository\AuthorRepository;

class AuthorsMagazineService
{
    private AuthorMagazinesRepository $authorMagazinesRepository;
    private AuthorRepository $authorRepository;

    public function __construct(AuthorMagazinesRepository $authorMagazinesRepository, AuthorRepository $authorRepository)
    {
        $this->authorMagazinesRepository = $authorMagazinesRepository;
        $this->authorRepository = $authorRepository;
    }
    public function getAuthorsMagazine(array $data, int $magazineId)
    {

        foreach ($data['authorIds'] as $authorId) {
            $authorMagazines[] = new AuthorMagazine($magazineId,$authorId);
        }

        $this->authorMagazinesRepository->save($authorMagazines);

        $authorsDB = $this->authorMagazinesRepository->findAuthorsMagazine($magazineId);

        foreach ($authorsDB as $author) {
            $authorDB = $this->authorRepository->findAuthor($author['author_id']);
            $authorsArr[] = $authorDB->toArray();
        }
        return ['authors' => $authorsArr];
    }
}