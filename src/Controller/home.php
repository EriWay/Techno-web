<?php

/** @var Twig\Environment $twig 
 * @var int $id
*/

use Symfony\Component\HttpFoundation\Response;

$id= $_GET['id'];
session_start();
print_r($_SESSION);

return new Response($twig->render('home/home.html.twig', ['session'=>$_SESSION,'sessionid'=> session_id(),'id' => $id, 'name'=> $_SESSION['username']]));