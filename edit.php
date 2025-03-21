<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

include 'bdd.php';

if (isset($_GET['table']) && isset($_GET['id'])) {
    $tableName = $_GET['table'];
    $id = $_GET['id'];

    try {
        // Connexion à la base de données avec gestion d'erreurs
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les données de la ligne spécifiée
        $stmt = $conn->prepare("SELECT * FROM $tableName WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si la ligne existe
        if (!$row) {
            echo "<p>Erreur : La ligne avec cet ID n'existe pas.</p>";
            exit();
        }

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit();
    }
} else {
    echo "<p>Erreur : Table ou ID non spécifié.</p>";
    exit();
}

// Gestion de l'envoi du formulaire (mise à jour des données)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère les données envoyées par le formulaire
    $updateData = [];
    foreach ($_POST as $column => $value) {
        // On ne permet pas de modifier les mots de passe
        if ($column !== 'password') {
            $updateData[$column] = $value;
        }
    }

    try {
        // Préparer la mise à jour
        $setClause = "";
        $params = [];
        foreach ($updateData as $column => $value) {
            $setClause .= "$column = :$column, ";
            $params[":$column"] = $value;
        }

        $setClause = rtrim($setClause, ", "); // Retirer la dernière virgule
        $params[':id'] = $id;

        // Exécuter la requête de mise à jour
        $stmt = $conn->prepare("UPDATE $tableName SET $setClause WHERE id = :id");
        $stmt->execute($params);

        // Redirection après la mise à jour
        header("Location: admin_dashboard.php");
        exit();

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit();
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les données</title>
    <link rel="stylesheet" href="style/admin.css">
</head>
<body>
    <h1>Modification des données de la table "<?php echo htmlspecialchars($tableName); ?>"</h1>
    <form action="edit.php?table=<?php echo htmlspecialchars($tableName); ?>&id=<?php echo $id; ?>" method="POST">
        <?php
        // Dynamique génération du formulaire
        foreach ($row as $column => $data) {
            // Ne pas afficher le champ "password" dans le formulaire
            if ($column === 'password') {
                continue;
            }

            echo "<label for='$column'>" . htmlspecialchars($column) . " :</label>";
            echo "<input type='text' name='$column' id='$column' value='" . htmlspecialchars($data) . "' required>";
            echo "<br><br>";
        }
        ?>
        <button type="submit">Enregistrer les modifications</button>
    </form>
    <p><a href="admin_dashboard.php">Retour à l'administration</a></p>
</body>
</html>
