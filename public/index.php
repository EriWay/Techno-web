<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\HttpFoundation\Session\Session;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Initialise la session Symfony
$session = new Session();
$session->start();

$request = Request::createFromGlobals();

// Chargement des routes
$routes = require_once dirname(__DIR__) . '/src/Routes/routes.php';

// Configuration de Symfony Routing
$context = new RequestContext();
$context->fromRequest($request);
$urlMatcher = new UrlMatcher($routes, $context);

// Chargement du moteur de template Twig
$loader = new FilesystemLoader(dirname(__DIR__) . '/templates');
$twig = new Environment($loader, [
    'cache' => false,
]);

// Initialisation de Doctrine
$entityManager = require_once dirname(__DIR__) . '/config/database.php';
$schemaTool = new SchemaTool($entityManager);
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();

// Création des tables si elles n'existent pas déjà
$schemaTool->updateSchema($metadata);

try {
    // Exécution de la route correspondante à la requête
    extract($urlMatcher->match($request->getPathInfo()));
    $response = require dirname(__DIR__) . '/src/Controller/' . $_route . '.php';
} catch (ResourceNotFoundException $exception) {
    $response = new Response('The requested page doesn\'t exist', Response::HTTP_NOT_FOUND);
}

$response->send();
