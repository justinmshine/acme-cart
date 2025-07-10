<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once 'object/cart.php';
require_once 'object/product.php';

class CartTest extends TestCase
{
    private Cart $cart;

    protected function setUp(): void
    {
        $this->cart = new Cart();
    }

    public function testAddItem(): void
    {
        $product = new Product(1, "Test Product", "TP01", 10.00, 5);
        $this->cart->addItem($product->getId(), $product->getName(), $product->getCode(), $product->getPrice(), 2);
        $this->assertCount(1, $this->cart->getItems());
    }

    public function testRemoveItem(): void
    {
        $product = new Product(1, "Test Product", "TP01", 10.00, 5);
        $this->cart->addItem($product->getId(), $product->getName(), $product->getCode(), $product->getPrice(), 2);
        $this->cart->removeItem($product->getId());
        $this->assertCount(0, $this->cart->getItems());
    }

    public function testGetTotal(): void
    {
        $product1 = new Product(1, "Test Product 1", "TP01", 10.00, 5);
        $product2 = new Product(2, "Test Product 2", "TP02", 20.00, 3);
        $this->cart->addItem($product1->getId(), $product1->getName(), $product1->getCode(), $product1->getPrice(), 2);
        $this->cart->addItem($product2->getId(), $product2->getName(), $product2->getCode(), $product2->getPrice(), 1);
        $this->assertEquals(40.00, $this->cart->getTotalPrice());
    }
}
