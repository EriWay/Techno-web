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


return new Response($twig->render('profil/profil.html.twig',['session'=> $_SESSION,'user'=>$res[0], 'id'=>$id]));