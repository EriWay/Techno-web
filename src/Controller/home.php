<?php

/** @var Twig\Environment $twig */

use Symfony\Component\HttpFoundation\Response;

$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    session_destroy();
}

session_start();
$sessionUsername = isset($_SESSION['username']) ? $_SESSION['username'] : null;

$sql='SELECT image, COUNT(*) OVER () AS total FROM meme';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$randomMeme=rand(0,$result[0][1]-1);

$memeAccueil=$result[$randomMeme][0];

$memeAccueil = base64_encode($memeAccueil);

return new Response($twig->render('home/home.html.twig', ['session' => $_SESSION, 'name' => $sessionUsername, 'memeaccueil'=>$memeAccueil]));
