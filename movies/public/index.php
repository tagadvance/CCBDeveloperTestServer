<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
$container = $app->getContainer();

$container['db'] = function($c) {
    $database = $user = $password = "sakila";
    $host = "mysql";

    return new PDO("mysql:host={$host};dbname={$database};charset=utf8", $user, $password);
};

$app->get('/movies', function (Request $request, Response $response, array $args) {
    $db = $this->get('db');

    return $response->withJson([]);
});

$app->run();
