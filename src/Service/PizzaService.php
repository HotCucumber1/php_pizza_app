<?php

namespace App\Service;

use App\Entity\Pizza;
use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PizzaService implements PizzaServiceInterface
{
    public function __construct(private readonly PizzaRepository $pizzaRepository,
                                private readonly ImageServiceInterface $imageService)
    {
    }

    public function addPizza(string  $pizzaName,
                             ?string $definition,
                             int     $weight,
                             float   $price,
                             UploadedFile  $pizzaImage,
                             ?string $pizzaType): int
    {
        $img = $this->imageService->moveImageToUploads($pizzaImage, $pizzaName);
        $pizza = new Pizza(null,
                            $pizzaName,
                            $definition,
                            $weight,
                            $price,
                            $img,
                            $pizzaType);
        return $this->pizzaRepository->store($pizza);
    }

    public function getPizza(int $pizzaId): Pizza
    {
        $pizza = $this->pizzaRepository->findPizzaById($pizzaId);
        if (is_null($pizza))
        {
            throw new BadRequestException("User not found");
        }
        return $pizza;
    }

    public function getListPizzas(): array
    {
        $pizzas = $this->pizzaRepository->findPizzaList();
        if (!$pizzas)
        {
            throw new BadRequestException('Pizzas not found');
        }
        return $pizzas;
    }
}