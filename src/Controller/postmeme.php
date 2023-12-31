<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Initialisation de la base de données SQLite
$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

session_start();

// Initialisation des erreurs
$errors = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Ajout : Obtenez l'ID de l'utilisateur à partir de la session
    $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

    // Vérifier si l'image est correctement uploadée
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $nameFile = $_FILES['image']['name'];
        $tmpFile = $_FILES['image']['tmp_name'];
        $typeFile = pathinfo($nameFile, PATHINFO_EXTENSION);

        $correctType = array("png", 'jpg', "gif");

        if (in_array($typeFile, $correctType)) {

                // Insérer les informations du meme dans la base de données
                $sql = "INSERT INTO meme (name, description, user_id, image, publicationDate) VALUES (:name, :description, :user_id, :image, :publicationDate)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':name', $nom);
                $stmt->bindValue(':description', $description);
                $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindValue(':image', file_get_contents($tmpFile), PDO::PARAM_LOB);
                $stmt->bindValue(':publicationDate', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                $stmt->execute();

                // Rediriger vers la page des memes
                header("Location: /meme");
                exit;

        } else {
            $errors[] = "Type de fichier non pris en charge. Utilisez les formats : png, jpg, gif.";
        }
    } else {
        $errors[] = "Erreur lors de l'upload de l'image.";
    }
}

return new Response($twig->render('meme/postmeme.html.twig', ['errors' => $errors, 'session'=>$_SESSION]));
