<?php

use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Driver\Connection;

/** @var Twig\Environment $twig 
 * @var Connection $connection
 * @var int $id
 */

$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

session_start();

// Récupération des paramètres GET
$id = isset($_GET['id']) ? $_GET['id'] : null;//id de l'image selectionee


// Requête SQL pour récupérer les memes depuis la base de données
if (!$id) {
    $sql = "SELECT * FROM meme";
    $memes = $connection->fetchAllAssociative($sql);

    // Assurez-vous d'avoir une colonne 'image' dans votre table meme
    foreach ($memes as &$meme) {
        // Assumez que 'image' est le nom de la colonne où vous stockez les images dans votre table
        $meme['image'] = base64_encode($meme['image']); // Convertir l'image en base64 pour l'affichage
    }
} else {
    $stmt = $pdo->prepare('SELECT * FROM meme WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $theMeme = $stmt->fetchAll();
    $theMeme= $theMeme[0];
    $theMeme['image'] = base64_encode($theMeme['image']);


}



// Vérifier si les clés existent avant de les utiliser
return new Response($twig->render('meme/meme.html.twig', ['memes' => $memes,'session'=>$_SESSION, 'id' => $id,'selectedmeme'=>$theMeme]));
