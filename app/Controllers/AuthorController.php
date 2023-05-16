<?php

namespace banana\Controllers;

use banana\Entity\Author;
use banana\Repository\AuthorRepository;

class AuthorController
{
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function index(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if (empty($errorMessages)) {

                $authors = $this->authorRepository->index();
            }
        }

        $authors = json_encode($authors, true);

        return $authors;
    }

    public function add(): string
    {
        $data = json_decode(json_encode(json_decode(file_get_contents('php://input'),true),true),true);

        $errorMessages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errorMessages = $this->validate($data);

            if (empty($errorMessages)) {

                $lastName = $this->clearData($data['lastName']);
                $firstName = $this->clearData($data['firstName']);
                $surname = $this->clearData($data['surname']);

                $author = new Author($lastName, $firstName, $surname);

                $this->authorRepository->save($author);

                $author = $author->toArray();
            }
        }

        $author = json_encode($author, true);

        $errorMessages = json_encode($errorMessages,true);

        return $author . $errorMessages;
    }

    public function update(): string
    {
        $data = json_decode(json_encode(json_decode(file_get_contents('php://input'),true),true),true);

        $errorMessages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errorMessages = $this->validate($data);

            if (empty($errorMessages)) {

                $id = $data['id'];

                $lastName = $this->clearData($data['lastName']);
                if ($lastName) {
                    $this->authorRepository->setLastName($lastName, $id);
                }

                $firstName = $this->clearData($data['firstName']);
                if ($firstName) {
                    $this->authorRepository->setFirstName($firstName, $id);
                }

                $surname = $this->clearData($data['surname']);
                if ($surname) {
                    $this->authorRepository->setSurname($surname, $id);
                }

                $author = $this->authorRepository->findAuthor($id);
            }
        }
        $author = $author->toArray();

        $author = json_encode($author, true);

        $errorMessages = json_encode($errorMessages,true);

        return $author . $errorMessages;
    }

    public function delete(): string
    {
        $data = json_decode(json_encode(json_decode(file_get_contents('php://input'),true),true),true);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errorMessages = $this->validateId($data['id']);

            if (empty($errorMessages)) {

                $delMessage =  $this->authorRepository->delete($data['id']);
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

    private function validate(array $data): array
    {
        $errorMessages = [];

        $lastNameError = $this->validateLastName($data);
        if (!empty($lastNameError)) {
            $errorMessages['lastName'] = $lastNameError;
        }

        $firstNameError = $this->validateFirstName($data);
        if (!empty($firstNameError)) {
            $errorMessages['firstName'] = $firstNameError;
        }

        $surnameError = $this->validateSurname($data);
        if (!empty($surnameError)) {
            $errorMessages['surname'] = $surnameError;
        }

        return $errorMessages;
    }

    private function validateLastName(array $data): string|null
    {
        $lastName = $data['lastName'] ?? null;
        if (empty($lastName)) {
            return 'Invalid Last Name';
        }

        if (strlen($lastName) < 2) {
            return 'Last Name is too short';
        }

        return null;
    }

    private function validateFirstName(array $data): string|null
    {
        $firstName = $data['firstName'] ?? null;
        if (empty($firstName)) {
            return 'Invalid First Name';
        }

        if (strlen($firstName) < 2) {
            return 'First Name is too short';
        }

        return null;
    }

    private function validateSurname(array $data): string|null
    {
        $surname = $data['surname'] ?? null;
        if (empty($surname)) {
            return 'Invalid Surname';
        }

        if (strlen($surname) < 5) {
            return 'Surname is too short';
        }

        return null;
    }

    private function validateId(int $id): string|null
    {
        if (!empty($id) && !is_int($id)) {
            return 'Invalid id';
        }

        return null;
    }
}