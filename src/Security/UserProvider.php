<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->userRepository->findUserByEmail($username);
        if ($user === null)
        {
            throw new UserNotFoundException($username);
        }
        return new SecurityUser($user->getUserId(),
                                $user->getEmail(),
                                $user->getPassword(),
                                $user->getRoles());
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findUserByEmail($identifier);
        if ($user === null)
        {
            throw new UserNotFoundException($identifier);
        }
        return new SecurityUser($user->getUserId(),
                                $user->getEmail(),
                                $user->getPassword(),
                                $user->getRoles());
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof SecurityUser)
        {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s"', get_class($user)));
        }
        $currentUser = $this->userRepository->findUserByEmail($user->getUserIdentifier());
        if ($currentUser === null)
        {
            throw new UserNotFoundException($user->getUserIdentifier());
        }
        return new SecurityUser($user->getId(),
                                $currentUser->getEmail(),
                                $currentUser->getPassword(),
                                $currentUser->getRoles());
    }

    public function supportsClass(string $class): bool
    {
        return SecurityUser::class === $class || is_subclass_of($class, SecurityUser::class);
    }
}