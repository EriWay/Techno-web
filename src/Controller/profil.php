<?php

/** @var Twig\Environment $twig */

use Symfony\Component\HttpFoundation\Response;

$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

session_start();

$sql="SELECT id, nom, prenom, email, avatar FROM utilisateurs WHERE id= :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $_SESSION["userid"]);
$stmt->execute();
$res = $stmt->fetchAll();


return new Response($twig->render('profil/profil.html.twig',['session'=> $_SESSION,'user'=>$res[0]]));