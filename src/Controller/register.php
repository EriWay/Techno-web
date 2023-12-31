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
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];
    $pseudo = $_POST['pseudo'];



    // Vérifiez si les mots de passe correspondent
    if ($mot_de_passe !== $confirmation_mot_de_passe) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }

    // Vérifiez si l'adresse e-mail existe déjà dans la base de données
    $emailExistsQuery = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email OR pseudo = :pseudo";
    $stmt = $pdo->prepare($emailExistsQuery);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();
    $emailExists = $stmt->fetchColumn();
    if ($emailExists) {
        $errors[] = "l'email ou le pseudo est déjà utilisé !!";
        return new Response($twig->render('register/register.html.twig', ['errors' => $errors]));
    }
    $mdp = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    // Si pas d'erreurs, insérez dans la base de données
    if (empty($errors) || $emailExists) {
        $avatarPath = "Avatars/Avatar";
        $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, avatar, pseudo) VALUES (:nom, :prenom, :email, :mot_de_passe, :avatar, :pseudo)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mdp);
        $stmt->bindParam(':avatar', $avatarPath);
        $stmt->bindParam(':pseudo', $pseudo);
        

        if ($stmt->execute()) {
            if($_FILES){
                $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = :email');
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $id = $stmt->fetchAll();

                $stmt = $pdo->prepare('UPDATE utilisateurs SET avatar = :avatar WHERE email = :email');
                $stmt->bindParam(':email', $email);
                $thePath = $avatarPath . $id[0][0] . ".png";
                $stmt->bindParam(':avatar', $thePath);
                $stmt->execute();

                $nameFile = $_FILES['avatar']['name'];
                $tmpFile = $_FILES['avatar']['tmp_name'];
                $typeFile = explode('.', $nameFile)[1];

                $correctType = array("png",'jpg');
                $uploadDir = dirname(dirname(__DIR__)) ."/public/Avatars/";

                if (in_array($typeFile, $correctType)) {
                    
                    if (move_uploaded_file($tmpFile,$uploadDir . "avatar" . $id[0][0] . ".png")) {
                        
                    }
                } else {
                    $erreur[] = " le format de l'image doit être en png ou en Jpeg";
                }
            }
            // Redirige l'utilisateur vers une autre page après l'inscription réussie
            session_start();
            $_SESSION['username'] = $pseudo;
            $_SESSION['userid'] = $id[0][0];
            $_SESSION['sessionid'] = session_id();

            return new Response($twig->render('register/confirmation.html.twig', ['session'=>$_SESSION]));

        } else {
            $errors[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
        }
    
    }
}

// Affichage du formulaire
return new Response($twig->render('register/register.html.twig', ['errors' => $errors]));