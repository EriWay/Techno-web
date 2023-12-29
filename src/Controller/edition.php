<?php

use Symfony\Component\HttpFoundation\Response;

// Initialisation de la base de donnÃ©es SQLite
$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

session_start();

$sql="SELECT id, nom, prenom, email, avatar, pseudo FROM utilisateurs WHERE id= :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $_SESSION["userid"]);
$stmt->execute();
$res = $stmt->fetchAll();
$res= $res[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];
    $pseudo = $_POST['pseudo'];

    $sql='UPDATE utilisateurs SET nom=:nom, prenom=:prenom, email=:email, pseudo=:pseudo WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id',$_SESSION['userid']);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();

    if($mot_de_passe != '') {
        if($mot_de_passe == $confirmation_mot_de_passe){
            $mdp = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $sql = 'UPDATE utilisateurs SET mot_de_passe = :mdp WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':mpd', $mdp);
            $stmt->bindParam(':id', $_SESSION['userid']);
            $stmt->execute();
        }
    }
    if($_FILES['avatar']['name']!=''){
        $avatarPath = "Avatars/Avatar";
        $stmt = $pdo->prepare('UPDATE utilisateurs SET avatar = :avatar WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $thePath = $avatarPath . $_SESSION['userid'] . ".png";
        $stmt->bindParam(':avatar', $thePath);
        $stmt->execute();

        $nameFile = $_FILES['avatar']['name'];
        $tmpFile = $_FILES['avatar']['tmp_name'];
        $typeFile = explode('.', $nameFile)[1];

        $correctType = array("png",'jpg');
        $uploadDir = dirname(dirname(__DIR__)) ."/public/Avatars/";

        if (in_array($typeFile, $correctType)) {
            echo"correct type file";
            if (move_uploaded_file($tmpFile,$uploadDir . "avatar" . $_SESSION['userid'] . ".png")) {
                echo"Uploaded !";
            }
        } else {
            echo"not correct type";
        }
    }
    header("Location: /profil?id=" . $_SESSION['userid']);
    exit();
}

return new Response($twig->render('profil/edition.html.twig', ['session'=>$_SESSION,'user'=>$res,'errors' => $errors]));