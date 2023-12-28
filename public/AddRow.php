<?php
try {
    // Connexion à la base de données SQLite
    $db_path = dirname(__DIR__) . '/src/DB/db.sqlite';
    $pdo = new PDO('sqlite:'.$db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Nom de la table que vous souhaitez vider
    $table_name = 'utilisateurs';

    // Utilisation de la requête DELETE pour vider la table
    $pdo->exec("ALTER TABLE $table_name ADD avatar VARCHAR(50)");

    echo "La table $table_name a été modifiée avec succès.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
