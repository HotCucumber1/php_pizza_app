<?php
namespace App\Model;


interface UserRepositoryInterface
{
    public function addUser(User $user): int;
    public function findUser(int $userId): ?User;
    public function updateUser(User $user): void;
    public function deleteUser(int $id): void;
    public function saveAvatarPathToDB(string $avatar, int $id): void;
}
