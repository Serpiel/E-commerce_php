
<?php include 'bdd.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="icon" type="image/x-icon" href="">
    <link rel="stylesheet" href="style/content.css">
</head>
<body>
    <header>
    <?php include 'navbar.php'; ?>
    </header>
    <main>
        <div class="header-container">
            <h2>Welcome, 
                <?php 
                    if (isset($_SESSION['username'])): ?>
                        <?= htmlspecialchars($_SESSION['username']);
                    endif;
                ?> ! &nbsp;&nbsp;</h2>
        </div>
    
        <p>Coming soon...</p>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>