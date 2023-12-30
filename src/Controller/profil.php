<?php

/** @var Twig\Environment $twig */

use Symfony\Component\HttpFoundation\Response;

$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

session_start();
$id = $_GET['id'];

$sql="SELECT id, nom, prenom, email, avatar, pseudo FROM utilisateurs WHERE id= :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$res = $stmt->fetchAll();
$_SESSION['username'] = $res[0]['pseudo'];

$sql2="SELECT * FROM meme WHERE user_id= :id";
$stmt = $pdo->prepare($sql2);
$stmt->bindParam(":id", $id);
$stmt->execute();
$memes = $stmt->fetchAll();
    if($memes){
        foreach ($memes as &$meme) {
            // Assumez que 'image' est le nom de la colonne où vous stockez les images dans votre table
            $meme['image'] = base64_encode($meme['image']); // Convertir l'image en base64 pour l'affichage
        }
    }
return new Response($twig->render('profil/profil.html.twig',['session'=> $_SESSION,'user'=>$res[0], 'id'=>$id, 'memes'=>$memes]));