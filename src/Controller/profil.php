<?php

/** @var Twig\Environment $twig */

use Symfony\Component\HttpFoundation\Response;

$id= $_GET['id'];

return new Response($twig->render('profil/profil.html.twig'));