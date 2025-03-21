<?php 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

include 'bdd.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['table'])) {
        $table = $_GET['table'];

        
        $stmt = $conn->query("SHOW COLUMNS FROM $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        $filteredColumns = [];
        foreach ($columns as $column) {
            if (strpos($column['Extra'], 'auto_increment') === false) {
                $filteredColumns[] = $column;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $values = [];
            $placeholders = [];
            $params = [];

            foreach ($filteredColumns as $column) {
                $columnName = $column['Field'];
                $columnType = $column['Type'];

                
                if (strpos($columnName, 'password') !== false) {
                    
                    $params[":$columnName"] = password_hash($_POST[$columnName], PASSWORD_DEFAULT);
                } else {
                    $params[":$columnName"] = $_POST[$columnName] ?? null;
                }

                $values[] = "`$columnName`"; 
                $placeholders[] = ":$columnName";
            }

            $valuesStr = implode(',', $values);
            $placeholdersStr = implode(',', $placeholders);

            $stmt = $conn->prepare("INSERT INTO `$table` ($valuesStr) VALUES ($placeholdersStr)");
            $stmt->execute($params);

            header("Location: admin_dashboard.php?table=$table&success=1");
            exit();
        }
    } else {
        echo "No table specified.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Entry</title>
    <link rel="stylesheet" href="style/admin.css">
</head>
<body>
    <h2>Add a new entry in the table "<?php echo htmlspecialchars($table); ?>"</h2>
    <?php if (!empty($filteredColumns)): ?>
        <form action="" method="POST">
            <?php foreach ($filteredColumns as $column): ?>
                <label for="<?php echo $column['Field']; ?>"><?php echo $column['Field']; ?></label>
                <?php 
                    $type = $column['Type'];
                    
                    if (strpos($type, 'int') !== false) {
                        echo '<input type="number" name="' . $column['Field'] . '" id="' . $column['Field'] . '" required>';
                    } elseif (strpos($type, 'varchar') !== false || strpos($type, 'text') !== false) {
                        echo '<input type="text" name="' . $column['Field'] . '" id="' . $column['Field'] . '" required>';
                    } elseif (strpos($type, 'date') !== false) {
                        echo '<input type="date" name="' . $column['Field'] . '" id="' . $column['Field'] . '" required>';
                    } elseif (strpos($type, 'password') !== false) {
                        echo '<input type="password" name="' . $column['Field'] . '" id="' . $column['Field'] . '" required>';
                    } else {
                        echo '<input type="text" name="' . $column['Field'] . '" id="' . $column['Field'] . '" required>';
                    }
                ?>
                <br>
            <?php endforeach; ?>
            <input class="submit-btn" type="submit" value="Add">
        </form>
    <?php else: ?>
        <p>Error: No valid columns found to insert data.</p>
    <?php endif; ?>
</body>
</html>
