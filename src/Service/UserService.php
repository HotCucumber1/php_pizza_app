<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly UserRepository $userRepository,
                                private readonly ImageServiceInterface $imageService)
    {
    }

    public function saveUser(string $name,
                             string $lastName,
                             ?string $middleName,
                             string $gender,
                             \DateTime $birtDate,
                             string $email,
                             ?string $phone,
                             ?string $avatarPath): int
    {
        $user = new User(
            null,
            $name,
            $lastName,
            $middleName,
            $gender,
            $birtDate,
            $email,
            $phone,
            $avatarPath,
        );
        return $this->userRepository->store($user);
    }

    public function getUser(int $userId): User
    {
        $user = $this->userRepository->findUserById($userId);
        if (is_null($user))
        {
            throw new BadRequestException("User not found");
        }
        return $user;
    }

    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findUserById($userId);
        $this->userRepository->delete($user);
    }

    public function updateAvatar(UploadedFile $avatar, int $userId): void
    {
        $user = $this->userRepository->findUserById($userId);
        $avatarPath = $this->imageService->moveImageToUploads($avatar, $userId);
        $user->setAvatarPath($avatarPath);
        $this->userRepository->store($user);
    }

    public function updateUser(int $userId,
                               string $name,
                               string $lastName,
                               ?string $middleName,
                               string $gender,
                               \DateTime $birtDate,
                               string $email,
                               ?string $phone,
                               ?UploadedFile $avatar): User
    {
        $user = $this->userRepository->findUserById($userId);
        if (is_null($user))
        {
            throw new BadRequestException("User not found");
        }

        $user->setFirstName($name);
        $user->setLastName($lastName);
        $user->setMiddleName($middleName);
        $user->setGender($gender);
        $user->setBirthDate($birtDate);
        $user->setEmail($email);
        $user->setPhone($phone);

        if ($avatar)
        {
            $avatarPath = $this->imageService->moveImageToUploads($avatar, $userId);
            $user->setAvatarPath($avatarPath);
        }

        $this->userRepository->store($user);
        return $user;
    }

    public function getListUsers(): array
    {
        $users = $this->userRepository->findUsersList();
        if (!$users)
        {
            throw new BadRequestException('Users not found');
        }
        return $users;
    }
}