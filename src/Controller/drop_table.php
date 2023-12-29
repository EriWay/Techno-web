<?php

try {
    // Chemin vers la base de données SQLite
    $dbPath = dirname(__DIR__) . '/DB/db.sqlite';

    // Connexion à la base de données SQLite
    $pdo = new PDO('sqlite:' . $dbPath);

    // Suppression de la table 'commentaire'
    $sql = "DROP TABLE IF EXISTS commentaire";
    $pdo->exec($sql);

    echo "Table 'commentaire' supprimée avec succès.";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>
