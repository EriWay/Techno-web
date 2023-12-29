<?php

use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Driver\Connection;

/** @var Twig\Environment $twig 
 * @var Connection $connection
 * @var int $id
 */

$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

session_start();

// Récupération des paramètres GET
$id = isset($_GET['id']) ? $_GET['id'] : null; // id de l'image sélectionnée

$theMeme = null; // Ajoutez cette ligne pour initialiser la variable
$commentaires = null; // Ajoutez cette ligne pour initialiser la variable
$memes = []; // Initialisez la variable $memes à un tableau vide

if (!$id) {
    $sql = "SELECT meme.*, utilisateurs.pseudo, utilisateurs.avatar 
            FROM meme 
            LEFT JOIN utilisateurs ON meme.user_id = utilisateurs.id";
    $memes = $connection->fetchAllAssociative($sql);

    // Assurez-vous d'avoir une colonne 'image' dans votre table meme
    foreach ($memes as &$meme) {
        // Assumez que 'image' est le nom de la colonne où vous stockez les images dans votre table
        $meme['image'] = base64_encode($meme['image']); // Convertir l'image en base64 pour l'affichage
    }
} else {
    $stmt = $pdo->prepare('SELECT meme.*, utilisateurs.pseudo, utilisateurs.avatar 
                          FROM meme 
                          LEFT JOIN utilisateurs ON meme.user_id = utilisateurs.id 
                          WHERE meme.id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $theMeme = $stmt->fetch();
    $theMeme['image'] = base64_encode($theMeme['image']);

    $commentaires = [];
    $stmt = $pdo->prepare('SELECT commentaire.*, utilisateurs.pseudo, utilisateurs.avatar 
                          FROM commentaire 
                          JOIN utilisateurs ON commentaire.userId = utilisateurs.id 
                          WHERE meme_id = :meme_id');
    $stmt->bindParam(':meme_id', $id);
    $stmt->execute();
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ajout d'un commentaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        $comment = trim($_POST['comment']); // Supprimer les espaces au début et à la fin du commentaire

        // Vérifier si le commentaire n'est pas vide
        if (!empty($comment)) {
            // Vérifier si l'utilisateur est connecté
            if (isset($_SESSION['userid'])) {
                $userId = $_SESSION['userid'];

                // Insérer le commentaire dans la base de données
                $sql = "INSERT INTO commentaire (userId, date, commentaire, meme_id) VALUES (:userId, :date, :commentaire, :memeId)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':date', date('Y-m-d H:i:s'));
                $stmt->bindParam(':commentaire', $comment);
                $stmt->bindParam(':memeId', $id);
                $stmt->execute();

                header('Location: /meme?id=' . $id); // Remplacez par l'URL souhaitée après la suppression
                exit();
            }
        }
    }

    // Suppression du commentaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-comment-id'])) {
        $commentIdToDelete = $_POST['delete-comment-id'];

        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['userid'])) {
            $userId = $_SESSION['userid'];

            // Récupérer l'auteur du commentaire
            $stmt = $pdo->prepare('SELECT userId FROM commentaire WHERE id = :commentId');
            $stmt->bindParam(':commentId', $commentIdToDelete);
            $stmt->execute();
            $commentAuthorId = $stmt->fetchColumn();

            // Vérifier si l'utilisateur est l'auteur du commentaire
            if ($commentAuthorId == $userId) {
                // L'utilisateur est l'auteur du commentaire, supprimer le commentaire
                $stmt = $pdo->prepare('DELETE FROM commentaire WHERE id = :commentId');
                $stmt->bindParam(':commentId', $commentIdToDelete);
                $stmt->execute();

                // Rediriger l'utilisateur après la suppression
                header('Location: /meme?id=' . $id); // Remplacez par l'URL souhaitée après la suppression
                exit();
            } else {
                // L'utilisateur n'est pas l'auteur du commentaire
                echo "Vous n'êtes pas l'auteur du commentaire."; // Message de débogage
            }
        } else {
            // L'utilisateur n'est pas connecté
            echo "Vous devez être connecté pour supprimer un commentaire."; // Message de débogage
        }
    }
    // Suppression du mème
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-meme']) && $_POST['delete-meme'] === 'true') {

        // Vérifier si la variable POST 'delete-meme' existe et contient la valeur attendue
        // Vérifier si la variable POST 'delete-meme-id' existe
        if (isset($_POST['delete-meme-id'])) {
            $deleteMemeId = $_POST['delete-meme-id'];

            // Vérifier si l'utilisateur est connecté
            if (isset($_SESSION['userid'])) {
                $userId = $_SESSION['userid'];

                // Récupérer l'auteur du mème
                $stmt = $pdo->prepare('SELECT user_id FROM meme WHERE id = :id');
                $stmt->bindParam(':id', $deleteMemeId);
                $stmt->execute();
                $memeAuthorId = $stmt->fetchColumn();

                // Vérifier si l'utilisateur est l'auteur du mème
                if ($memeAuthorId == $userId) {
                    // L'utilisateur est l'auteur du mème, supprimer le mème
                    $stmt = $pdo->prepare('DELETE FROM meme WHERE id = :id');
                    $stmt->bindParam(':id', $deleteMemeId);
                    $stmt->execute();

                    // Rediriger l'utilisateur après la suppression
                    header('Location: /meme'); // Remplacez par l'URL souhaitée après la suppression
                    exit();
                } else {
                    // L'utilisateur n'est pas l'auteur du mème
                    echo "Vous n'êtes pas l'auteur du mème."; // Message de débogage
                }
            } else {
                // L'utilisateur n'est pas connecté
                echo "Vous devez être connecté pour supprimer un mème."; // Message de débogage
            }
        } else {
            // La variable POST 'delete-meme-id' est manquante
            echo "L'identifiant du mème à supprimer est manquant."; // Message de débogage
        }
    }
}

// Vérifier si les clés existent avant de les utiliser
return new Response($twig->render('meme/meme.html.twig', [
    'memes' => $memes,
    'session' => $_SESSION,
    'id' => $id,
    'selectedmeme' => $theMeme,
    'commentaires' => $commentaires
]));
