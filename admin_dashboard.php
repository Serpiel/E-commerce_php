<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

include 'bdd.php';

try {
    // Connexion à la base de données avec gestion d'erreurs
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les tables de la base de données de manière sécurisée
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error : " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style/admin.css">
</head>
<body>
    <h1>Welcome, Emperor</h1>

    <h2>List of the tables of "<?php echo htmlspecialchars($dbname); ?>"</h2>

    <?php 
    if ($tables) {
        foreach ($tables as $table) {
            $tableName = $table['Tables_in_' . $dbname];  // Utilisation dynamique du nom de la table

            echo "<div class='table-container'>";
            echo "<h3>Table : $tableName</h3>";

            // Sécurisation de la requête SELECT
            $stmt = $conn->prepare("SELECT * FROM $tableName");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rows) {
                echo "<table>";
                echo "<tr>";

                // Affichage des en-têtes des colonnes
                foreach (array_keys($rows[0]) as $column) {
                    echo "<th>" . htmlspecialchars($column) . "</th>";
                }
                echo "<th>Actions</th>";
                echo "</tr>";

                foreach ($rows as $row) {
                    $idColumn = array_keys($rows[0])[0];

                    // Sauter l'ID 0 (si nécessaire, à confirmer selon les spécifications)
                    if ($row[$idColumn] == 0) {
                        continue;
                    }

                    echo "<tr>";
                    foreach ($row as $column => $data) {
                        if ($column === 'password') {
                            echo "<td>*****</td>"; // Masquage des mots de passe
                        } else {
                            echo "<td>" . htmlspecialchars($data) . "</td>";
                        }
                    }

                    echo "<td class='actions'>";
                    echo "<a class='edit' href='edit.php?table=$tableName&id={$row[$idColumn]}'>Edit</a>";
                    echo "<br>";
                    echo "<a class='delete' href='delete.php?table=$tableName&id={$row[$idColumn]}' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No data available in this table : $tableName.</p>";
            }
            echo "<p><a class='add' href='create.php?table=$tableName'>Add</a></p>";
            echo "</div>";
        }
    } else {
        echo "<p>No table found in the database.</p>";
    }

    $conn = null;
    ?>
</body>
</html>
