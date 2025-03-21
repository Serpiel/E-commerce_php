<?php
session_start();
include 'bdd.php';

if (!isset($_SESSION['username'])) {
    header("Location: connection.php");
    exit();

}

$username = $_SESSION['username'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newUsername = htmlspecialchars(trim($_POST["username"]));
        $newEmail = htmlspecialchars(trim($_POST["email"]));
        $newPassword = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null;

        try {
            $sql = "UPDATE users SET username = :username, email = :email" . ($newPassword ? ", password = :password" : "") . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $newUsername);
            $stmt->bindParam(':email', $newEmail);
            if ($newPassword) {
                $stmt->bindParam(':password', $newPassword);
            }
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();

            $_SESSION['username'] = $newUsername;
            $_SESSION['email'] = $newEmail;

            echo "<p style='color: green;'>Informations upgraded successfully.</p>";
        } catch (PDOException $e) {
            echo "Error : " . $e->getMessage();
        }
    }
} catch (PDOException $e) {
    echo "Error : " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="style/sign.css">
</head>
<body>
    <?php include 'navbar.php';?>
    <div class="profile-container">
        <h2>My Profile</h2>
        <form action="account.php" method="POST">
            <div class="InputBox">
                <input type="text" id="username" name="username" value="<?php htmlspecialchars($user['username']); ?>" required>
                <i>Username : </i>
            </div>

            <div class="InputBox">
                <input type="email" id="email" name="email" value="<?php htmlspecialchars($user['email']); ?>" required>
                <i>Email : </i>
            </div>

            <div class="InputBox"></div>
                <input type="password" id="password" name="password" required>
                <i>New Password : </i>
            </div>

            <div class="InputBox">
            <input type="submit" value="Update">
            </div>
            
        </form>
    </div>
    <?php include 'footer.php';?>
</body>
</html>
