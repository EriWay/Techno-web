<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('hello', new Route('/hello/{name}', ['name' => 'World']));
$routes->add('fibonacci', new Route('/fibonacci/{nb}', ['nb' => 10], ['nb' => '^[1-9][0-9]*$']));
$routes->add('register', new Route('/register'));
$routes->add('connect', new Route('/connect'));
$routes->add('profil', new Route('/profil'));
$routes->add('postmeme', new Route('/meme/post'));
$routes->add('meme', new Route('/meme/{id}',['id'=> -1]));
$routes->add('home', new Route('/{id}',['id'=> -1]));

return $routes;