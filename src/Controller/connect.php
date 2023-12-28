<?php

use Symfony\Component\HttpFoundation\Response;

// Initialisation de la base de données SQLite
$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

// Initialisation des erreurs
$errors = [];


// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Si pas d'erreurs, retourne à l'acceuil
    if (empty($errors)) {
        $query = "SELECT nom,prenom,mot_de_passe,id FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $user=array(0=>$res[0][0],1=>$res[0][1],2=>$res[0][2],3=>$res[0][3]);
        if (password_verify($mot_de_passe,$user[2])) {
            // Redirigez l'utilisateur vers une autre page après l'inscription réussie
            return new Response($twig->render('home/home.html.twig',['user' => $user,'id'=> $user[3]]));

        } else {
            $errors[] = "l'adresse email ou le mot de passe est invalide";
        }
    
    }
}

// Affichage du formulaire
return new Response($twig->render('connect/connect.html.twig', ['errors' => $errors]));