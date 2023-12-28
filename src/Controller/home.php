<?php

/** @var Twig\Environment $twig 
 * @var int $id
*/

use Symfony\Component\HttpFoundation\Response;

$id= $_GET['id'];

return new Response($twig->render('home/home.html.twig', ['id' => $id]));