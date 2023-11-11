<?php require "header.php"; ?>

<?php require "connexion.php"; ?>


<?php
// Si la table n'existe pas, on la crée
$sql = "CREATE TABLE IF NOT EXISTS Data (
    id SERIAL PRIMARY KEY,
    some_string VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
$pdo->exec($sql);

// Au cas où la page soit rechargé pour l'ajout d'un message dans la table
// Dans ce cas, la page est rechargée après le clic sur le bouton "envoyer" de la section "Ajouter".
// Le message est passé au travers de la validation d'un formulaire sont les données sont traitées avec
// la métode POST.
if (isset($_POST['message'])) {
    $stmt = $pdo->prepare('SELECT * FROM Data');
    $stmt->execute(array());
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) < 15) {
        $sql = "INSERT INTO Data(some_string) values (:message)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array('message' => htmlspecialchars($_POST['message'])));
    }
}

// Au cas où la page soit accéder avec une demande de suppression.
// Dans ce cas, l'id du message à supprimer est passé directement dans
// la barre d'adresse
if (isset($_GET['remove']) && isset($_GET['id'])) {
    $sql = "DELETE FROM Data WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('id' => htmlspecialchars($_GET['id'])));
}

// Récupération des données dans la base de données
$stmt = $pdo->prepare('SELECT * FROM Data');
$stmt->execute(array());
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mise en forme des données dans un tableau
if( count($results) == 0 )
    $rows = '<table><tr><td><strong>Pas de données en base.</strong></td></tr></table>';
else {
    $rows = '<table>';
    // Pour chaque tuple dans la table
    foreach($results as $key => $value) {
        $rows .= "<tr><th> {$value['id']} </th><td> {$value['some_string']} </td><td> {$value['created_at']} </td>";
        // On crée un lien de suppression qui sera appliqué
        // sur un caractère "croix rouge" pour indiquer une suppression.
        $rows .= '<td><a href="?remove&id=' . $value['id'] . '">❌</a></td>';
        $rows .= '</tr>';
    }
    $rows .= '</table>';
}

?>
        <div>
            <form action ="index.php" method="POST" onsubmit="javascript:return valide_ajouter(this);">
            <fieldset>
                <legend>Ajouter</legend>
                <label for id="input_some_string">Message :</label> 
                <input type="text" name="message" />
                <input type="submit" />
            </form>
        </div>
        <script type="text/javascript">
            function valide_ajouter(frm) {
                if(frm.message.value.length < 1){
                    frm.message.focus();
                    alert('Veuillez entrer un message à ajouter en base.');
                    return false;
                }
                return true;
            }
        </script>
        <br />

        <?= $rows ?>

        <br />
        <hr>
        <br />
        <div>
            <form action ="recherche.php" method="POST" onsubmit="javascript:return valide_recherche(this);">
            <fieldset>
                <legend>Recherche</legend>
                <label for id="input_some_string">Message :</label> 
                <input type="text" name="message" />
                <input type="submit" />
            </form>
        </div>
        <script type="text/javascript">
            function valide_recherche(frm) {
                if(frm.message.value.length < 1){
                    frm.message.focus();
                    alert('Veuillez entrer une chaine à recherche.');
                    return false;
                }
                return true;
            }
        </script>
        

<?php require "footer.php"; ?>
