<?php

namespace App\Security;

use App\Entity\UserRole;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(private int $id,
                                private string $email,
                                private string $password,
                                private string $role)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        if ($this->role === UserRole::ADMIN)
        {
            return ['ROLE_ADMIN', 'ROLE_USER'];
        }
        return ['ROLE_USER'];
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}