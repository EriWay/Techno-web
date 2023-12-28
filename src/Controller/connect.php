<?php

use Symfony\Component\HttpFoundation\Response;

// Initialisation de la base de données SQLite
$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

// Initialisation des erreurs
$errors = [];

// Vérifiez si la méthode est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Traitement du formulaire
    // Si pas d'erreurs, retourne à l'accueil
    if (empty($errors)) {
        $query = "SELECT nom, prenom, mot_de_passe, id FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($query);

        // Vérifiez si la préparation de la requête a réussi
        if ($stmt) {
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $res = $stmt->fetchAll();

            // Vérifiez si la requête a retourné des résultats
            if (!empty($res)) {
                $user = [
                    'nom' => $res[0]['nom'],
                    'prenom' => $res[0]['prenom'],
                    'mot_de_passe' => $res[0]['mot_de_passe'],
                    'id' => $res[0]['id']
                ];

                if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                    session_start();
                    $_SESSION['username'] = $user['nom'];
                    $_SESSION['userid'] = $user['id'];
                    $_SESSION['sessionid'] = session_id();
                    header("Location: /");
                    die();
                } else {
                    $errors[] = "L'adresse email ou le mot de passe est invalide";
                }
            } else {
                $errors[] = "L'adresse email ou le mot de passe est invalide";
            }
        } else {
            // Gestion de l'erreur de préparation de la requête
            $errors[] = "Erreur de préparation de la requête SQL";
        }
    }
}

// Affichage du formulaire
return new Response($twig->render('connect/connect.html.twig', ['errors' => $errors]));
