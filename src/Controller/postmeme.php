<?php

use Symfony\Component\HttpFoundation\Response;

// Initialisation de la base de données SQLite
$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

// Initialisation des erreurs
$errors = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];

    $nameFile = $_FILES['image']['name'];
    $tmpFile = $_FILES['image']['tmp_name'];
    $typeFile = explode('.', $nameFile)[1];

    $correctType = array("png",'jpg',"gif");
    $uploadDir = dirname(dirname(__DIR__)) ."\public\memeFile/";

    if (in_array($typeFile, $correctType)) {
        echo"correct type file";
        if (move_uploaded_file($tmpFile,$uploadDir . $nom . "." . $typeFile)) {
            echo"Uploaded !";
        }
    } else {
        echo"not correct type";
    }
}

return new Response($twig->render('meme/postmeme.html.twig', ['errors' => $errors]));