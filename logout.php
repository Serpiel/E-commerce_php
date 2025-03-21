<?php
    session_start();
    include 'bdd.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <h1>You were disconnected</h1>
    <form method="post" action="welcome.php" <?php session_destroy() ?>>
        <button type="submit" name="return-site">Back to the store</button>
    </form>
</body>
</html>