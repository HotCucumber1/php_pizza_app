<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use App\Entity\User;

class UserRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function findUserById(int $userId): ?User
    {
        return $this->entityManager->find(User::class, $userId);
        // return $this->repository->findOneBy(["userId" => (string) $userId]);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function store(User $user): int
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user->getUserId();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findUsersList(): array
    {
        return $this->repository->findAll();
    }
}