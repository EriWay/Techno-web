<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Initialisation de la base de données SQLite
$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

$id = $_GET['id'];

// Initialisation des erreurs
$errors = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Vérifier si l'image est correctement uploadée
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $nameFile = $_FILES['image']['name'];
        $tmpFile = $_FILES['image']['tmp_name'];
        $typeFile = pathinfo($nameFile, PATHINFO_EXTENSION);

        $correctType = array("png", 'jpg', "gif");

        if (in_array($typeFile, $correctType)) {
            // Chemin où l'image sera stockée
            $uploadDir = dirname(dirname(__DIR__)) . "/public/memeFile/";
            $uploadPath = $uploadDir . $nom . "." . $typeFile;

            // Déplacer l'image vers le répertoire d'upload
            if (move_uploaded_file($tmpFile, $uploadPath)) {
                // Insérer les informations du meme dans la base de données
                $sql = "INSERT INTO meme (name, description, image, publicationDate) VALUES (:name, :description, :image, :publicationDate)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':name', $nom);
                $stmt->bindValue(':description', $description);
                $stmt->bindValue(':image', file_get_contents($uploadPath), PDO::PARAM_LOB);
                $stmt->bindValue(':publicationDate', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                $stmt->execute();

                // Rediriger vers la page des memes
                header("Location: /meme?id={$id}");
                exit;
            } else {
                $errors[] = "Erreur lors de l'upload de l'image.";
            }
        } else {
            $errors[] = "Type de fichier non pris en charge. Utilisez les formats : png, jpg, gif.";
        }
    } else {
        $errors[] = "Erreur lors de l'upload de l'image.";
    }
}

return new Response($twig->render('meme/postmeme.html.twig', ['errors' => $errors, 'id' => $id]));
