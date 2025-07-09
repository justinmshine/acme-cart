<?php

class Product
{
    private int $id;
    private string $name;
    private string $code;
    private float $price;
    private int $quantity;

    // Constructor
    public function __construct(int $id, string $name, string $code, float $price, int $quantity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new InvalidArgumentException("Price cannot be negative.");
        }
        $this->price = $price;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException("Quantity cannot be negative.");
        }
        $this->quantity = $quantity;
    }

    // Calculate total value (price * quantity)
    public function getTotalValue(): float
    {
        return $this->price * $this->quantity;
    }
}