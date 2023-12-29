<?php

/** @var Twig\Environment $twig */

use Symfony\Component\HttpFoundation\Response;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    session_destroy();
}

session_start();
$sessionUsername = isset($_SESSION['username']) ? $_SESSION['username'] : null;

return new Response($twig->render('home/home.html.twig', ['session' => $_SESSION, 'name' => $sessionUsername]));
