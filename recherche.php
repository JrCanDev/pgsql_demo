<?php require "header.php"; ?>

<?php require "connexion.php"; ?>


<?php
// Si la table n'existe pas, on la crée
$sql = "CREATE TABLE IF NOT EXISTS Data (
    id SERIAL PRIMARY KEY,
    some_string VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
$pdo->exec($sql);

// Récupération des données dans la base de données
$stmt = $pdo->prepare("SELECT * FROM Data WHERE upper(some_string) like upper('%". htmlspecialchars($_POST['message']) ."%')");
$stmt->execute(array());
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mise en forme des données dans un tableau
if(count($results) == 0)
    $rows = 'Résultat pour la recherche de <strong>' . htmlspecialchars($_POST['message']) . '</strong> :<br /><br />Aucun résultat.';
else{
    $rows = 'Résultat pour la recherche de <strong>' . htmlspecialchars($_POST['message']) . '</strong> :<br /><br /><table>';
    // Pour chaque tuple dans la table
    foreach($results as $key => $value) {
        $rows .= "<tr><th> {$value['id']} </th><td> {$value['some_string']} </td><td> {$value['created_at']} </td>";
        $rows .= '</tr>';
    }
    $rows .= '</table>';
}

?>
        <div>
            <form action ="recherche.php" method="POST">
            <fieldset>
                <legend>Recherche</legend>
                <label for id="input_some_string">Message:</label> 
                <input type="text" name="message">
                <input type="submit">
            </form>
        </div>
        <br />

        <?= $rows ?>

        <br />
        <center><a href="index.php">Retour à l'accueil</a></center>

<?php require "footer.php"; ?>
