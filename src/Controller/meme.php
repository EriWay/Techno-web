<?php

/** @var Twig\Environment $twig 
 * @var int $id
*/

use Symfony\Component\HttpFoundation\Response;

$id= $_GET['id'];
$memename = $_GET['name'];

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

return new Response($twig->render('meme/meme.html.twig', ['mem' => $memes, 'id'=>$id, 'memename'=>$memename]));