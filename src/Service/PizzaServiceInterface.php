<?php

namespace App\Service;

use App\Entity\Pizza;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PizzaServiceInterface
{
    public function addPizza(string  $pizzaName,
                             ?string $definition,
                             int     $weight,
                             float   $price,
                             UploadedFile  $pizzaImage,
                             ?string $pizzaType): int;
    public function getPizza(int $pizzaId): Pizza;
    public function getListPizzas(): array;
}