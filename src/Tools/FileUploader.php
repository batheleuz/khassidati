<?php

namespace App\Tools;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploader
{
    private $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $fileName = uniqid('khassidati-').'.'.$file->guessExtension();

        $file->move(
            $this->targetDirectory,
            $fileName
        );

        return $fileName;
    }
}
