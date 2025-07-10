<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once 'object/product.php';

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product(1, "Test Product", "TP01", 10.00, 5);
    }

    public function testGetId(): void
    {
        $this->assertEquals(1, $this->product->getId());
    }

    public function testGetName(): void
    {
        $this->assertEquals("Test Product", $this->product->getName());
    }

    public function testGetCode(): void
    {
        $this->assertEquals("TP01", $this->product->getCode());
    }

    public function testGetPrice(): void
    {
        $this->assertEquals(10.00, $this->product->getPrice());
    }

    public function testGetQuantity(): void
    {
        $this->assertEquals(5, $this->product->getQuantity());
    }

    public function testSetQuantity(): void
    {
        $this->product->setQuantity(10);
        $this->assertEquals(10, $this->product->getQuantity());
    }

    public function testSetPrice(): void
    {
        $this->product->setPrice(15.00);
        $this->assertEquals(15.00, $this->product->getPrice());
    }

    public function testSetName(): void
    {
        $this->product->setName("Updated Product");
        $this->assertEquals("Updated Product", $this->product->getName());
    }

    public function testSetCode(): void
    {
        $this->product->setCode("UP01");
        $this->assertEquals("UP01", $this->product->getCode());
    }
}