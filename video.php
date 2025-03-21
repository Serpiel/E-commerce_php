<?php
session_start();

include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $video_id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $video_id]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$video) {
            die("Video not found");
        }
    } else {
        die("ID video not specified");
    }
} catch (PDOException $e) {
    echo "Error : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($video['name']); ?></title>
    <link rel="stylesheet" href="style/video.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="video-details">
            <h1><?= htmlspecialchars($video['name']); ?></h1>
            <video controls>
                <source src="<?= htmlspecialchars($products['video']); ?>" type="video/mp4">
                Your browser doesn't support the videos.
            </video>
            <p>
                <strong>Description :</strong> <?= htmlspecialchars($video['description']); ?>
            </p>
            <div class="details-container">
                <div class="details-left">
                    <p>
                        <strong>Upload Date :</strong> <?= htmlspecialchars($video['upload_date']); ?><br>
                        <strong>Duration :</strong> <?= htmlspecialchars($video['duration']); ?>
                    </p>
                </div>
            </div>
            <div class="details-right">
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($video['id']); ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>