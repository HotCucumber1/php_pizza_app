<?php

namespace App\Repository;

use App\Entity\Pizza;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PizzaRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Pizza::class);
    }

    public function findPizzaById(int $pizzaId): ?Pizza
    {
        return $this->entityManager->find(Pizza::class, $pizzaId);
    }

    public function store(Pizza $pizza): int
    {
        $this->entityManager->persist($pizza);
        $this->entityManager->flush();
        return $pizza->getPizzaId();
    }

    public function delete(Pizza $pizza): void
    {
        $this->entityManager->remove($pizza);
        $this->entityManager->flush();
    }

    public function findPizzaList(): array
    {
        return $this->repository->findAll();
    }
}