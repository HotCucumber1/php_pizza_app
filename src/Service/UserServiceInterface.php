<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserServiceInterface
{
    public function saveUser(string $name,
                             string $lastName,
                             string $email,
                             ?string $phone,
                             ?string $avatarPath,
                             string $password): int;

    public function getUserById(int $userId): User;
    public function getUserByEmail(string $email): User;
    public function isPasswordRight(User $user, string $password): bool;
    public function isAdmin(User $user): bool;
    public function deleteUser(int $userId): void;
    public function getListUsers(): array;
    public function updateUser(int $userId,
                               string $name,
                               string $lastName,
                               string $email,
                               ?string $phone,
                               UploadedFile $avatar): User;

    public function updateAvatar(UploadedFile $avatar, int $userId): void;
}