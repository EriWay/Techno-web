<?php

use Symfony\Component\HttpFoundation\Response;

// Initialisation des erreurs
$errors = [];

// Vérifiez si la méthode est POST (c'est-à-dire si le formulaire de déconnexion est soumis)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialise la session
    session_start();

    // Déconnecte l'utilisateur en réinitialisant la session
    $_SESSION = [];
    session_destroy();

    // Affichez le contenu de la session (elle devrait être vide après la déconnexion)
    var_dump($_SESSION);

    // Redirigez l'utilisateur vers la page d'accueil après la déconnexion
    return new Response($twig->render('logout.html.twig'));
}
