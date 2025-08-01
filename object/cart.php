<?php
class Cart
{
    private $items;

    public function __construct()
    {
        // Initialize cart from session or as empty array
        $this->items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    /**
     * Add a product to the cart or update quantity if it exists.
     *
     * @param int|string $productId
     * @param string $name
     * @param string $code
     * @param float $price
     * @param int $quantity
     * @return void
     */
    public function addItem($productId, string $name, string $code, float $price, int $quantity = 1): void
    {
        if ($quantity < 1) {
            return;
        }

        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $this->items[$productId] = [
                'name'     => $name,
                'code'     => $code,
                'price'    => $price,
                'quantity' => $quantity,
            ];
        }
        $this->save();
    }

    /**
     * Remove product from the cart
     *
     * @param int|string $productId
     * @return bool True if removed, false if not found
     */
    public function removeItem($productId): bool
    {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
            $this->save();
        }
        return false;
    }

    /**
     * Update the quantity of an existing product in the cart.
     * If quantity <= 0, it will remove the item.
     *
     * @param int|string $productId
     * @param int $quantity
     * @return bool True if updated, false if product not found
     */
    public function updateItemQuantity($productId, int $quantity): bool
    {
        if (!isset($this->items[$productId])) {
            return false;
        }

        if ($quantity <= 0) {
            $this->removeItem($productId);
        } else {
            $this->items[$productId]['quantity'] = $quantity;
            $this->save();
        }
        return false;
    }

    /**
     * Get all items in the cart
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Get total item count (sum of quantities)
     *
     * @return int
     */
    public function getTotalQuantity(): int
    {
        $totalQuantity = 0;
        foreach ($this->items as $item) {
            $totalQuantity += $item['quantity'];
        }
        return $totalQuantity;
    }

    /**
     * Get total price for all items
     *
     * @return float
     */
    public function getTotalPrice(): float
    {
        $totalPrice = 0.0;
        foreach ($this->items as $key => $item) {
            if($key == 1) {
                // Get second half price for Red Widget R01
                $halfAmount = $item['quantity'] / 2;
                $halfAmount = floor($halfAmount);
                $fullPrice = $item['quantity'] - $halfAmount;
                $totalPrice += $fullPrice * $item['price'];
                $totalPrice += $halfAmount * ($item['price'] / 2);

            }
            else {
                $totalPrice += $item['price'] * $item['quantity'];
            }
        }
        return $totalPrice;
    }

    /**
     * Get shipping cost based on total price
     *
     * @return float
     */
    public function getShipping(float $total): float
    {
        if ($total > 90) {
            return 0.0; // Free shipping for orders over $90
        }
        if ($total < 50) {
            return 4.95; // Flat rate shipping for orders under $50
        }
        return 2.95; // Flat rate shipping between $50 and $90
    }

    /**
     * Empty the cart
     *
     * @return void
     */
    public function clear(): void
    {
        $this->items = [];
        $_SESSION['cart'] = $this->items;
    }

    private function save()
    {
        $_SESSION['cart'] = $this->items;
    }
}

?>