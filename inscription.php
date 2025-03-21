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
            <h1>Sign Up</h1>
            <form action="" method="POST" onsubmit="return validateForm()">
                <div class="InputBox">
                <input type="text" id="username" name="username" required>
                <i>Username</i>
                </div>

                <div class="InputBox">
                <input type="email" id="email" name="email" required>
                <i>Email</i>
                </div>

                <div class="InputBox">
                <input type="password" id="password" name="password" required>
                <i>Password</i>
                </div>

                <div class="InputBox">
                <input type="password" id="confirm_password" name="confirm_password" required>
                <i>Confirm Password</i>
                </div>

                <div class="InputBox">
                <input type="submit" value="Submit">
                </div>

            </form>
            <p>Already registered ? <a href="connection.php" class="register-link">Log in here</a></p>
        </div>
        <p id="error_msg"></p>
</div>

    <script src="Interactions/script.js"></script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars(trim($_POST["username"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm_password"]));
        

        if ($password != $confirm_password) {
            echo "<p style='color: red'>Passwords do not match.</p>";
            exit();
        }

        include "bdd.php";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<p style='color: red'>Email already exists.</p>";
            } else {
                $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedpassword);

                if ($stmt->execute()) {
                    header("Location: welcome.php");
                    exit();
                } else {
                    echo "<p style='color: green'>Registration successful!</p>";
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    }
    ?>
    <?php include 'footer.php'; ?>
</body>
</html>