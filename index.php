<!Doctype html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acme Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
        require_once 'object/product.php';

        $product = [];
        $product[] = new Product(1, "Red Widget", "R01",  32.05, 20);
        $product[] = new Product(2, "Green Widget", "G01",  24.95, 30);
        $product[] = new Product(3, "Blue Widget", "B01",  7.95, 15);
    ?>

    <div class="container">
        <header>
            <h1>Acme Cart</h1>
        </header>
        <div class="products">
            <?php foreach ($product as $item) { ?>
                <div class="product">
                    <h2><?php echo $item->getName(); ?></h2><p class="stock">Stock: <?php echo $item->getQuantity(); ?></p>
                    <p>Code: <?php echo $item->getCode(); ?></p>
                    <p>Price: $<?php echo number_format($item->getPrice(), 2); ?></p>
                    <div class="purchase">
                        <input type="hidden" name="id" value="<?php echo $item->getId(); ?>">
                        <input type="hidden" name="product_code" value="<?php echo $item->getCode(); ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $item->getQuantity(); ?>">
                        <button class="add-to-cart" type="submit">Add to Cart</button>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="cart">
            <h2>Shopping Cart</h2>
            <table id="cart-table">
                <thead>
                    <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
                        <td colspan="2" id="cart-total">$0.00</td>
                    </tr>
                </tfoot>
            </table>
            <button id="clear-cart">Clear Cart</button>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadCart() {
                $.ajax({
                    url: 'cart_action.php',
                    type: 'POST',
                    data: { action: 'get' },
                    dataType: 'json',
                    success: function(res) {
                        console.log('Cart loaded:', res);
                        if (res.status === 'success') {
                            let tbody = '';
                            const items = res.items;
                            if ($.isEmptyObject(items)) {
                                tbody = '<tr><td colspan="5">Cart is empty</td></tr>';
                            } else {
                                $.each(items, function(productId, item) {
                                    const subtotal = (item.price * item.quantity).toFixed(2);
                                    tbody += `<tr data-productId="${productId}">
                                        <td>${item.name}</td>
                                        <td>$${item.price.toFixed(2)}</td>
                                        <td><input type="number" min="1" class="quantity-input" value="${item.quantity}" style="width:50px" readonly></td>
                                        <td>$${subtotal}</td>
                                        <td><button class="remove-item">Remove</button></td>
                                    </tr>`;
                                });
                            }
                            $('#cart-table tbody').html(tbody);
                            $('#cart-total').text('$' + parseFloat(res.total).toFixed(2));
                        }
                    },
                    error: function() {
                        alert('Failed to load cart.');
                    }
                });
            }

            loadCart();

            $('.add-to-cart').click(function() {
                console.log('Add to cart button clicked');
                const id = $(this).siblings('input[name="id"]').val();
                const code = $(this).siblings('input[name="product_code"]').val();
                const quantity = $(this).siblings('input[name="quantity"]').val();

                console.log('Adding to cart:', code, quantity);

                $.ajax({
                    url: 'cart_action.php',
                    type: 'POST',
                    data: {
                        action: 'add',
                        productId: id,
                        productCode: code,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(res) {
                        console.log('Added to Cart:', res);
                        if (res.status === 'success') {
                            loadCart();
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });

            $('#cart-table').on('click', '.remove-item', function() {
                const productId = $(this).closest('tr').data('productid');

                $.ajax({
                    url: 'cart_action.php',
                    type: 'POST',
                    data: { action: 'remove', productId: productId },
                    dataType: 'json',
                    success: function(res){
                        if (res.status === 'success') {
                            loadCart();
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });

            $('#cart-table').on('change', '.quantity-input', function() {
                const $row = $(this).closest('tr');
                const productId = $row.data('productid');
                const quantity = parseInt($(this).val());

                if (quantity > 0) {
                    $.ajax({
                        url: 'cart_action.php',
                        type: 'POST',
                        data: {
                            action: 'update',
                            productId: productId,
                            quantity: quantity
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                loadCart();
                            } else {
                                alert(res.message);
                            }
                        }
                    });
                } else {
                    alert('Quantity must be at least 1');
                    $(this).val(1);
                }
            });

            $('#clear-cart').click(function() {
                if (confirm('Are you sure you want to clear the cart?')) {
                    $.ajax({
                        url: 'cart_action.php',
                        type: 'POST',
                        data: { action: 'clear' },
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                loadCart();
                            } else {
                                alert(res.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html> 