<?php 
session_start();
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart_items)) {
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit purchase</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <h1>Submit purchase</h1>

    <?php if (!empty($cart_items)): ?>
        <p>You have <?php echo count($cart_items); ?>articles in your cart.</p>

        <form method="post" action="process_order.php">
            <label for="address">Delivery address : </label><br>
            <input type="text" id="address" name="address required">

            <label for="payment">Payment method : </label><br>
            <select id="payment" name="payment_method">
                <option value="credit_card">Credit card</option>
                <option value="paypal">PayPal</option>
            </select><br>

            <button type="submit">Finalize purchase</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>