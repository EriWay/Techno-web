<?php

/** @var Twig\Environment $twig 
 * @var int $id
*/

use Symfony\Component\HttpFoundation\Response;

return new Response($twig->render('home/home.html.twig', ['id' => $id]));