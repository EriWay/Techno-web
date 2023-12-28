<?php

/** @var Twig\Environment $twig 
 * @var int $id
*/

use Symfony\Component\HttpFoundation\Response;

// Récupération des paramètres GET
$id = isset($_GET['id']) ? $_GET['id'] : null;
$memename = isset($_GET['name']) ? $_GET['name'] : null;

$folder = 'memeFile/'; 
$memes = array();

try {
    if (is_dir($folder)) {
        $memes = glob($folder . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    } else {
        echo 'Le répertoire n\'existe pas.';
    }
} catch (Exception $e) {
    echo 'Exception : ',  $e->getMessage(), "\n";
}

// Vérifier si les clés existent avant de les utiliser
return new Response($twig->render('meme/meme.html.twig', ['mem' => $memes, 'id' => $id, 'memename' => $memename]));
