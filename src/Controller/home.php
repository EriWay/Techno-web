<?php

/** @var Twig\Environment $twig */

use Symfony\Component\HttpFoundation\Response;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    echo'Deconnexion';
    session_destroy();
}

session_start();
print_r($_SESSION);

return new Response($twig->render('home/home.html.twig', ['session'=>$_SESSION, 'name'=> $_SESSION['username']]));