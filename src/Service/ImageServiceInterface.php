<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageServiceInterface
{
    public function moveImageToUploads(UploadedFile $file, int $userId): ?string;


}