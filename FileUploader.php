<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $username, $fileId)
    {

        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $parts = explode('.', $fileName);
        $replace = str_replace($parts[0], $username . $fileId . '.', $parts);
        $finalName = implode($replace);

        try {
            $file->move($this->getTargetDirectory(), $finalName);
        } catch (FileException $e) {
            echo "Une erreur est survenue lors du téléchargement de l'image.";
        }

        return $finalName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}