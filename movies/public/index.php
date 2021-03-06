<?php

require '../vendor/autoload.php';

use persistence\MovieDao;
use rest\Hateoas;

$configuration = [
    'settings' => [
        // Note: for debugging only. Disable in "production".
        'displayErrorDetails' => true
    ]
];
$container = new \Slim\Container($configuration);
$app = new \Slim\App($container);

$container['pdo'] = function ($c) {
    $host = 'mysql';
    $database = $user = $password = 'sakila';
    
    $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    return new PDO($dsn, $user, $password, $options);
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
