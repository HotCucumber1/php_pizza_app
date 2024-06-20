<?php

namespace App\Entity;

class Pizza
{
    public function __construct(private ?int    $pizzaId,
                                private string  $pizzaName,
                                private ?string $definition,
                                private int     $weight,
                                private float   $price,
                                private string  $pizzaImage,
                                private ?string $pizzaType)
    {
    }

    /**
     * @return int|null
     */
    public function getPizzaId(): ?int
    {
        return $this->pizzaId;
    }

    /**
     * @return string
     */
    public function getPizzaName(): string
    {
        return $this->pizzaName;
    }

    /**
     * @return string
     */
    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getPizzaImage(): string
    {
        return $this->pizzaImage;
    }

    /**
     * @return string|null
     */
    public function getPizzaType(): ?string
    {
        return $this->pizzaType;
    }

    /**
     * @param string $pizzaName
     */
    public function setPizzaName(string $pizzaName): void
    {
        $this->pizzaName = $pizzaName;
    }

    /**
     * @param string $definition
     */
    public function setDefinition(string $definition): void
    {
        $this->definition = $definition;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
    /**
     * @param int $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @param string|null $pizzaImage
     */
    public function setPizzaImage(?string $pizzaImage): void
    {
        $this->pizzaImage = $pizzaImage;
    }

    /**
     * @param string|null $pizzaType
     */
    public function setPizzaType(?string $pizzaType): void
    {
        $this->pizzaType = $pizzaType;
    }
}