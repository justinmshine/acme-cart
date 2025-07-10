<?php
session_start();
require_once 'object/cart.php';
require_once 'object/product.php';

$product = [];
$product[] = new Product(1, "Red Widget", "R01",  32.05, 20);
$product[] = new Product(2, "Green Widget", "G01",  24.95, 30);
$product[] = new Product(3, "Blue Widget", "B01",  7.95, 15);

header('Content-Type: application/json');

$cart = new Cart();

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        // Expected POST data: id, code, quantity
        $productId = trim($_POST['productId']);
        $productCode = trim($_POST['productCode']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $item = [];

        foreach ($product as $prod) {
            if ($prod->getCode() === $productCode) {
                error_log("Items match so add in to items", 3, "errors.log");
                $item = [
                    'id'       => $prod->getId(),
                    'name'     => $prod->getName(),
                    'code'     => $prod->getCode(),
                    'price'    => $prod->getPrice(),
                    'quantity' => $quantity
                ];
                break;
            }
        }

        if ($item && $quantity > 0) {
            $cart->addItem($item['id'], $item['name'], $item['code'], $item['price'], $quantity);
            echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
        break;

    case 'remove':
        $productId = trim($_POST['productId']);
        if ($productId) {
            $cart->removeItem($productId);
            echo json_encode(['status' => 'success', 'message' => 'Item removed from cart']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
        }
        break;

    case 'update':
        $productId = trim($_POST['productId']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
        if ($productId && $quantity >= 0) {
            $cart->updateItem($productId, $quantity);
            echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
        break;

    case 'get':
        error_log("In the get cart action", 3, "errors.log");
        $items = $cart->getItems();
        $total = $cart->getTotalPrice();
        error_log(print_r($items, true), 3, "errors.log");
        error_log(print_r($total, true), 3, "errors.log");
        echo json_encode(['status' => 'success', 'items' => $items, 'total' => $total]);
        break;

    case 'clear':
        $cart->clear();
        echo json_encode(['status' => 'success', 'message' => 'Cart cleared']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;  
}