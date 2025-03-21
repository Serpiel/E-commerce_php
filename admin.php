<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    include 'bdd.php';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND id=0");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password, $admin['password'])) {
                $_SESSION['admin'] = $admin['username'];
                echo "<p style='color:green;'>Admin connexion successful !</p>";
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "<p style='color:red;'>Incorrect password.</p>";
            }
        } 
        else {
            echo "<p style='color:red;'>Admin not found or wrong identifiant.</p>";
        }
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
    $conn = null;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Connection</title>
    <link rel="stylesheet" href="style/sign.css">
</head>
<body>
    <div class="login-container">
        <div class="form-container">
            <div>
                <h2>Admin Connection</h2>
                <form action="admin.php" method="POST">
                    <label for="email">Email : </label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password : </label>
                    <input type="password" id="password" name="password" required>

                    <input type="submit" value="Connect">
                </form>
            </div>
        </div>
    </div>
</body>
</html>