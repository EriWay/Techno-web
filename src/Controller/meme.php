<?php

use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Driver\Connection;

/** @var Twig\Environment $twig 
 * @var Connection $connection
 * @var int $id
 */

// Récupération des paramètres GET
$id = isset($_GET['id']) ? $_GET['id'] : null;
$memename = isset($_GET['name']) ? $_GET['name'] : null;

// Requête SQL pour récupérer les memes depuis la base de données
$sql = "SELECT * FROM meme";
$memes = $connection->fetchAllAssociative($sql);

// Assurez-vous d'avoir une colonne 'image' dans votre table meme
foreach ($memes as &$meme) {
    // Assumez que 'image' est le nom de la colonne où vous stockez les images dans votre table
    $meme['image'] = base64_encode($meme['image']); // Convertir l'image en base64 pour l'affichage
}

// Vérifier si les clés existent avant de les utiliser
return new Response($twig->render('meme/meme.html.twig', ['memes' => $memes, 'id' => $id, 'memename' => $memename]));
