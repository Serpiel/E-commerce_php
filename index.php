<?php session_start();

include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, name, image, video, price FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection error : " . $e->getMessage();
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $product_id;
    header("Location:cart.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products catalog</title>
    <link rel="stylesheet" href="style/products.css">
</head>
<body>
    <?php include 'navbar.php' ?>

    <h1>Main</h1>
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <div class="product-image-container">
                <img class="product-image" src="<?= htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <video class="product-video" autoplay loop muted>
                    <source src="<?= htmlspecialchars($product['video']); ?>" type="video/mp4">
                    Your browser doesn't support the videos.
                </video>
                </div>
                <h2>
                    <a href="video.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                </h2>
                
                <p>Price: €<?php echo htmlspecialchars($product['price']); ?></p>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <button class="product-add-cart" type="submit" name="add_to_cart">Add to cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="cart.php"> See the cart</a>

    <?php include 'footer.php'; ?>
</body>
</html>