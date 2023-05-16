<?php

namespace banana\Controllers;

use banana\Entity\Magazine;
use banana\Service\AuthorsMagazineService;
use banana\Service\MagazineService;
use banana\Repository\AuthorMagazinesRepository;
use banana\Repository\AuthorRepository;
use banana\Repository\MagazineRepository;

class MagazineController
{
    private MagazineRepository $magazineRepository;
    private AuthorMagazinesRepository $authorMagazinesRepository;
    private AuthorRepository $authorRepository;
    private MagazineService $magazineService;
    private AuthorsMagazineService $authorsMagazineService;

    public function __construct(MagazineRepository $magazineRepository,
                                AuthorMagazinesRepository $authorMagazinesRepository,
                                AuthorRepository $authorRepository,
                                MagazineService $magazineService,
                                AuthorsMagazineService $authorsMagazineService)
    {
        $this->magazineRepository = $magazineRepository;
        $this->authorMagazinesRepository = $authorMagazinesRepository;
        $this->authorRepository = $authorRepository;
        $this->magazineService = $magazineService;
        $this->authorsMagazineService = $authorsMagazineService;
    }

    public function index(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if (empty($errorMessages)) {

                $magazines = $this->magazineRepository->index();

                $authorsMagazine = [];

                foreach ($magazines as $magazine) {
                    $magazineId = $magazine['id'];

                    $authorsDB = $this->authorMagazinesRepository->findAuthorsMagazine($magazineId);

                    $authorsArr = array();

                    foreach ($authorsDB as $author) {
                        $authorDB = $this->authorRepository->findAuthor($author['author_id']);
                        $authorsArr[] = $authorDB->toArray();
                    }

                    $authors = ['authors' => $authorsArr];

                    $authorsMagazine[] = array_merge($magazine, $authors);
                }
            }
        }
        $authorMagazines = json_encode($authorsMagazine, true);

        return $authorMagazines;
    }

    public function add(): string
    {
        define("UPLOAD_DIR", "/var/www/uploads/");

        $data = $_POST;

        $errorMessages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errorMessages = $this->validate($data);

            if (empty($errorMessages)) {

                $name = $this->clearData($data['name']);
                $shortDescription = $this->clearData($data['shortDescription']);
                $magazineReleaseDate = $this->clearData($data['magazineReleaseDate']);
                $photoPath = UPLOAD_DIR;
                $photoName = $this->clearData($_FILES['photo']['name']);
                $photoUrl = $this->magazineService->savePhoto();

                $magazine = new Magazine($name, $shortDescription, $photoPath, $photoName, $magazineReleaseDate, $photoUrl);

                $this->magazineRepository->save($magazine);

                $magazineId =  $magazine->getId();

                $magazine = $magazine->toArray();

                $authors = $this->authorsMagazineService->getAuthorsMagazine($data, $magazineId);

                $authorsMagazine = array_merge($magazine, $authors);

            }
        }
        $authorMagazines = json_encode($authorsMagazine, true);

        $errorMessages = json_encode($errorMessages,true);

        return $authorMagazines . $errorMessages;
    }

    public function update(): string
    {
        define("UPLOAD_DIR", "/var/www/uploads/");

        $data = $_POST;

        $errorMessages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errorMessages = $this->validate($data);

            if (empty($errorMessages)) {

                $id = $data['id'];

                $name = $this->clearData($data['name']);
                if ($name) {
                    $this->magazineRepository->setName($name, $id);
                }

                $shortDescription = $this->clearData($data['shortDescription']);
                if ($shortDescription) {
                    $this->magazineRepository->setShortDescription($shortDescription, $id);
                }

                $magazineReleaseDate = $this->clearData($data['magazineReleaseDate']);
                if ($magazineReleaseDate) {
                    $this->magazineRepository->setMagazineReleaseDate($magazineReleaseDate, $id);
                }

                $photoName = $this->clearData($_FILES['photo']['name']);
                if ($photoName) {
                    $this->magazineRepository->updatePhotoName($photoName, $id);
                }

                $photoPath = UPLOAD_DIR;

                if ($photoPath) {
                    $this->magazineRepository->updatePhotoPath($photoPath, $id);
                }

                $photoUrl = $this->magazineService->savePhoto();
                if ($photoUrl) {
                    $this->magazineRepository->updatePhotoUrl($photoUrl, $id);
                }

                $magazine = $this->magazineRepository->findMagazine($id);

                $magazineId =  $magazine->getId();

                $this->authorMagazinesRepository->deleteAuthorsMagazine($magazineId);

                $magazine = $magazine->toArray();

                $authors = $this->authorsMagazineService->getAuthorsMagazine($data, $magazineId);

                $authorsMagazine = array_merge($magazine, $authors);
            }
        }

        $authorMagazines = json_encode($authorsMagazine, true);

        $errorMessages = json_encode($errorMessages,true);

        return $authorMagazines . $errorMessages;
    }

    public function delete(): string
    {
        $data = json_decode(json_encode(json_decode(file_get_contents('php://input'),true),true),true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errorMessages = $this->validateId($data['id']);

            if (empty($errorMessages)) {

                $id = $data['id'];

                $this->authorMagazinesRepository->deleteAuthorsMagazine($id);

                $delMessage =  $this->magazineRepository->delete($id);
            }
        }

        return $delMessage . $errorMessages;
    }

    private function clearData(string $val): string
    {
        $val = trim($val);
        $val = stripslashes($val);
        $val = strip_tags($val);
        return htmlspecialchars($val);
    }

    private function validate(array|null $data): array
    {
        $errorMessages = [];

        $nameError = $this->validateName($data);
        if (!empty($nameError)) {
            $errorMessages['name'] = $nameError;
        }

        $shortDescriptionError = $this->validateShortDescription($data);
        if (!empty($shortDescriptionError)) {
            $errorMessages['shortDescription'] = $shortDescriptionError;
        }

        $magazineReleaseDateError = $this->validateMagazineReleaseDate($data);
        if (!empty($magazineReleaseDateError)) {
            $errorMessages['magazineReleaseDate'] = $magazineReleaseDateError;
        }

        return $errorMessages;
    }

    private function validateId(int $id): string|null
    {
        if (!empty($id) && !is_int($id)) {
            return 'Invalid id';
        }

        return null;
    }

    private function validateName(array|null $data): string|null
    {
        $name = $data['name'] ?? null;
        if (empty($name)) {
            return 'Invalid name';
        }

        if (strlen($name) < 2) {
            return 'Name is too short';
        }

        return null;
    }

    private function validateShortDescription(array|null $data): string|null
    {
        $shortDescription = $data['shortDescription'] ?? null;

        if (strlen($shortDescription) > 500) {
            return 'Short Description is too long';
        }

        return null;
    }

    private function validateMagazineReleaseDate(array|null $data): string|null
    {
        $magazineReleaseDate = $data['magazineReleaseDate'] ?? null;
        if (empty($magazineReleaseDate)) {
            return 'Invalid Magazine Release Date';
        }

        if (strlen($magazineReleaseDate) < 8 || strlen($magazineReleaseDate) > 10) {
            return 'Magazine Release Date is too invalid';
        }

        return null;
    }
}