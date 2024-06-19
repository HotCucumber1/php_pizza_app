<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use http\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService implements ImageServiceInterface
{
    private const ALLOWED_MIME_TYPES_MAP = ['png',
                                            'jpeg',
                                            'gif',
                                            'jpg'];
    private const UPLOADS_PATH = "./uploads";

    public function __construct(UserRepository $userRepository)
    {
    }

    public function moveImageToUploads(UploadedFile $file, int $userId): ?string
    {
        if (!$file->isValid())
        {
            return null;
        }
        $imageExt = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();

        if (!in_array($imageExt, self::ALLOWED_MIME_TYPES_MAP))
        {
            throw new InvalidArgumentException("File '{$fileName}' has non-image type '{$imageExt}'");
        }

        $destFileName = "avatar" . "{$userId}" . "." . $imageExt;
        return $this->moveFileToUploads($file, $destFileName);
    }

    private function getUploadPath(): string
    {
        return self::UPLOADS_PATH . DIRECTORY_SEPARATOR;
    }

    private function moveFileToUploads(UploadedFile $file, string $destFileName): string
    {
        $fileName = $file->getClientOriginalName();
        $destPath = $this->getUploadPath();
        if (!$file->move($destPath, $destFileName))
        {
            throw new \RuntimeException("Failed to upload file {$fileName}");
        }
        return "." . $destPath . $destFileName;
    }
}