<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    include "bdd.php";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                echo "<p style='color: green;' Login successful! </p";
                header("Location: welcome.php");
                exit();
            } else {
                echo "<p style='color: red'>Invalid password.</p>";
            }
        } else {
            echo "<p style='color: red'>Email not found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red'>Error : </p>" . $e->getMessage();
    }
    $conn=null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription page</title>
    <link rel="stylesheet" href="style/sign.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <video autoplay muted loop id="myVideo">
        <source src="Assets/animus.mp4" type="video/mp4">
    </video>
    <div class="sign-container">
        <div class="content">
            <h1>Log In</h1>
            <form action="" method="POST">

                <div class="InputBox">
                <input type="email" id="email" name="email" required>
                <i>Email</i>
                </div>

                <div class="InputBox">
                <input type="password" id="password" name="password" required>
                <i>Password</i>
                </div>

                <div class="InputBox">
                <input type="submit" value="Log In">
                </div>
                
            </form>
        </div>
        <p>Not registered ? <a href="inscription.php" class="register-link">Register here</a></p>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>