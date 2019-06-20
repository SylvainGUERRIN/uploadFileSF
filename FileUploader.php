<?php


namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectoryAvatar;

    private $targetDirectoryImageArticle;

    public function __construct($targetDirectoryAvatar, $targetDirectoryImageArticle)
    {
        $this->targetDirectoryAvatar = $targetDirectoryAvatar;
        $this->targetDirectoryImageArticle = $targetDirectoryImageArticle;
    }

    public function upload(UploadedFile $file, $name, $fileId, $choiceTarget, $oldImage)
    {
        //création du nom de l'image définitive
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $parts = explode('.', $fileName);
        $replace = str_replace($parts[0], $name . $fileId . '.', $parts);
        $finalName = implode($replace);
        //suppression de l'ancienne image si elle existe pour l'article
        if($oldImage !== null) {
            $filesystem = new Filesystem();
            if($choiceTarget === 1) {
                $url = '../url/pour/les/avatars' . $oldImage;
            }else {
                $url = '../url/pour/les/images' . $oldImage;
            }
            $verif = $filesystem->exists($url);
            if ($verif === true) {
                $filesystem->remove($url);
            }
        }
        //enregistrement de l'image avec le repertoire correspondant
        if($choiceTarget === 1) {
            try {
                $file->move($this->getTargetDirectoryAvatar(), $finalName);
            } catch (FileException $e) {
                echo "Une erreur est survenue lors du téléchargement de l'image.";
            }
            return $finalName;
        }else{
            try {
                $file->move($this->getTargetDirectoryImageArticle(), $finalName);
            } catch (FileException $e) {
                echo "Une erreur est survenue lors du téléchargement de l'image.";
            }
            return $finalName;
        }
    }

    public function getTargetDirectoryAvatar()
    {
        return $this->targetDirectoryAvatar;
    }

    public function getTargetDirectoryImageArticle()
    {
        return $this->targetDirectoryImageArticle;
    }
}
