<?php

use Symfony\Component\HttpFoundation\Response;

// Initialisation de la base de données SQLite
$dbPath = dirname(__DIR__) . '/DB/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

// Initialisation des erreurs
$errors = [];
echo 'isConnected: ' . $_SESSION['isConnected'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Si pas d'erreurs, retourne à l'acceuil
    if (empty($errors)) {
        $query = "SELECT nom,prenom FROM utilisateurs WHERE email = :email AND mot_de_passe = :mot_de_passe";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $user=array(0=>$res[0][0],1=>$res[0][1]);

        if ($res) {

            $session->set('isConnected', true);
            // Après avoir défini la variable de session isConnected à true
            $_SESSION['isConnected'] = true;
            
            // Ajoutez ceci pour afficher le contenu de la session
            var_dump($_SESSION);
            // Redirigez l'utilisateur vers une autre page après l'inscription réussie
            return new Response($twig->render('home/home.html.twig', [
                'user' => $user,
                'isConnected' => $_SESSION['isConnected'] ?? false, // Utilise la valeur par défaut false si la session n'est pas définie
            ]));
            
        } else {
            $errors[] = "l'adresse email ou le mot de passe est invalide";
        }
    
    }
}

// Affichage du formulaire
return new Response($twig->render('connect/connect.html.twig', ['errors' => $errors]));