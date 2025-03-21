<?php
include 'bdd.php';

if (isset($_GET['query'])) {
    $search = htmlspecialchars($_GET['query']);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=$dbname", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error connexion : " . $e->getMessage());
    }

    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :query");
    $stmt->execute(['query' => '%' . $search . '%']);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="" href="">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <br><br><br><br>
    <h1>Results for "<?= htmlspecialchars($search) ?>"</h1>
    <div class="product-list"> 
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $product): ?>
                <div class="product-item">
                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?php htmlspecialchars($product['name']); ?>">
                    <video controls>
                        <source src="<?= htmlspecialchars($product['video']); ?>" type="video/mp4">
                        Your browser doesn't support the videos.
                    </video>
                    <h2>
                        <a href="video.php?id=<?= htmlspecialchars($product['id']); ?>">
                            <?= htmlspecialchars($product['name']); ?>
                        </a>
                    </h2>
                    <p>Price : €<?= htmlspecialchars($product['price']) ?></p>
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found for the search.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>