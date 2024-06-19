<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UserServiceInterface
{
    public function saveUser(string $name,
                             string $lastName,
                             ?string $middleName,
                             string $gender,
                             \DateTime $birtDate,
                             string $email,
                             ?string $phone,
                             ?string $avatarPath): int;

    public function getUser(int $userId): User;
    public function deleteUser(int $userId): void;
    public function getListUsers(): array;
    public function updateUser(int $userId,
                               string $name,
                               string $lastName,
                               ?string $middleName,
                               string $gender,
                               \DateTime $birtDate,
                               string $email,
                               ?string $phone,
                               UploadedFile $avatar): User;

    public function updateAvatar(UploadedFile $avatar, int $userId): void;
}