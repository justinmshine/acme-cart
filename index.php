<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acme Cart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php

    require_once 'object/product.php';
    require_once 'object/cart.php';

    $product = [];
    $product[] = new Product(1, "Red Widget", "R01",  32.05, 20);
    $product[] = new Product(2, "Green Widget", "G01",  24.95, 30);
    $product[] = new Product(3, "Blue Widget", "B01",  7.95, 15);

    $cart = new Cart();
    ?>

    <div class="container">
        <header>
            <h1>Acme Cart</h1>
        </header>
        <div class="products">
            <?php foreach ($product as $item) { ?>
                <div class="product">
                    <h2><?php echo $item->getName(); ?></h2>
                    <p>Code: <?php echo $item->getCode(); ?></p>
                    <p>Price: $<?php echo number_format($item->getPrice(), 2); ?></p>
                    <p>Quantity: <?php echo $item->getQuantity(); ?></p>
                    <p>Total Value: $<?php echo number_format($item->getTotalValue(), 2); ?></p>
                </div>
            <?php } ?>
        </div>
        <div class="cart">
            <h2>Shopping Cart</h2>
            <?php
            $cart->addItem(1, "Red Widget", "R01", 32.05, 2);
            $cart->addItem(2, "Green Widget", "G01", 24.95, 1);
            $cart->addItem(3, "Blue Widget", "B01", 7.95, 3);
            ?>
            <p>Total Items: <?php echo $cart->getTotalQuantity(); ?></p>
            <p>Total Price: $<?php echo number_format($cart->getTotalPrice(), 2); ?></p>
    </div>
</body>
</html> 