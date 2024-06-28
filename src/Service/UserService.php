<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserRole;
use App\Repository\UserRepository;
use Symfony\Component\Config\Definition\Exception\ForbiddenOverwriteException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly UserRepository $userRepository,
                                private readonly UserPasswordHasherInterface $hasher,
                                private readonly ImageServiceInterface $imageService)
    {
    }

    public function saveUser(string $name,
                             string $lastName,
                             string $email,
                             ?string $phone,
                             ?string $avatarPath,
                             string $password): int
    {
        if (!$this->isValid($name,
                            $lastName,
                            $email,
                            $phone,
                            $password))
        {
            throw new \InvalidArgumentException("User data is not valid");
        }
        $existingUser = $this->userRepository->findUserByEmail($email);

        if ($existingUser)
        {
            throw new \InvalidArgumentException('User with email "' . $email . '" has already been registered');
        }

        $user = new User(
            null,
            $name,
            $lastName,
            null,
            $email,
            $phone,
            $avatarPath,
        );

        $hashedPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        return $this->userRepository->store($user);
    }

    public function getUserById(int $userId): User
    {
        $user = $this->userRepository->findUserById($userId);
        if (is_null($user))
        {
            throw new BadRequestException("User not found");
        }
        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $user = $this->userRepository->findUserByEmail($email);
        if (is_null($user))
        {
            throw new BadRequestException("User not found");
        }
        return $user;
    }

    public function isPasswordRight(User $user, string $password): bool
    {
        return $this->hasher->isPasswordValid($user, $password);
    }

    public function isAdmin(User $user): bool
    {
        return UserRole::isAdmin($user->getRoles());
    }

    public function updateAvatar(UploadedFile $avatar, int $userId): void
    {
        $user = $this->userRepository->findUserById($userId);
        $avatarPath = $this->imageService->moveImageToUploads($avatar, (string)$userId);
        $user->setAvatarPath($avatarPath);
        $this->userRepository->store($user);
    }

    private function isValid(string $name,
                             string $lastName,
                             string $email,
                             ?string $phone,
                             string $password): bool
    {
        if (trim($name) === '' ||
            trim($lastName) === '' ||
            trim($email) === '' ||
            trim($password) == '')
        {
            return false;
        }
        if (!$this->checkEmail($email))
        {
            return false;
        }
        if (trim($phone) !== '' && !$this->checkNumber($phone))
        {
            return false;
        }
        return true;
    }

    private function checkEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function checkNumber(string $number): bool
    {
        return is_numeric($number);
    }
}