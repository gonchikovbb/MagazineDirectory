<?php

namespace banana\Service;
class MagazineService
{
    public function savePhoto()
    {
        $storage = '/storage/uploads/';

        $photoName = $this->clearData($_FILES['photo']['name']);

        $url = base64_encode(strstr($photoName, '.', true));

        $photoUrl = $storage . $url . '.png';

        if (!empty($_FILES["photo"])) {
            $myFile = $_FILES["photo"];

            if ($myFile["error"] !== UPLOAD_ERR_OK) {
                echo "Произошла ошибка";
                exit;
            }

            // проверяем файл GIF, JPEG или PNG
            $fileType = exif_imagetype($_FILES["photo"]["tmp_name"]);
            $allowed = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
            if (!in_array($fileType, $allowed)) {
                echo "тип файла не разрешен";
                return false;
            }

            $photoNameCode = base64_encode(strstr($photoName, '.', true));
            // сохраняем файл из временного каталога
            $success = move_uploaded_file($myFile["tmp_name"],
                UPLOAD_DIR . $photoNameCode . '.png');

            if (!$success) {
                echo "Не удалось сохранить файл";
                exit;
            }
        }

        return $photoUrl;
    }
    private function clearData(string $val): string
    {
        $val = trim($val);
        $val = stripslashes($val);
        $val = strip_tags($val);
        return htmlspecialchars($val);
    }
}