<?php

require '../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use persistence\MovieDao;
use tools\Hateoas;

$configuration = [
    'settings' => [
        // Note: for debugging only. Disable in "production".
        'displayErrorDetails' => true
    ]
];
$container = new \Slim\Container($configuration);
$app = new \Slim\App($container);

$container['pdo'] = function ($c) {
    $database = $user = $password = 'sakila';
    $host = 'mysql';
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    return new PDO("mysql:host={$host};dbname={$database};charset=utf8mb4", $user, $password, $options);
};
$container['movieDao'] = function ($c) {
    return new MovieDao($c['pdo']);
};
$container['baseHateoas'] = $container->factory(function ($c) {
    $hateoas = new Hateoas();
    $hateoas->addLink('/', 'start')->addLink('/movies', 'movies');
    return $hateoas;
});

$app->get('/', routes\StartRoute::class);
$app->get('/movies', routes\MoviesRoute::class);
$app->get('/movie/{id}', routes\MovieRoute::class);
$app->get('/movie/{id}/actors', routes\ActorsRoute::class);

$app->run();
