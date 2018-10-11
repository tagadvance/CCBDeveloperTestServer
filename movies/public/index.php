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

$app->get('/', function (Request $request, Response $response, array $args) {
    $hateoas = new Hateoas();
    $hateoas->addLink('/', 'start')->addLink('/movies', 'movies');
    $hateoas->addText('welcome', 'Welcome to Tag\s Developer Test Server.');
    return $response->withJson($hateoas->export());
});

$app->get('/movies', function (Request $request, Response $response, array $args) {
    $movieDao = $this->get('movieDao');
    
    $hateoas = new Hateoas();
    $hateoas->addLink('/', 'start')->addLink('/movies', 'movies');
    $hateoas->addNamedCollection('ratings', $movieDao->getFilmRatings());
    $hateoas->addNamedCollection('categories', $movieDao->getCategories());
    $hateoas->addText('hint', 'Movies may be filtered by title, rating, or category, e.g. /movies?title=dino&rating=PG&category=Classics');
    
    $params = $request->getQueryParams();
    $title = $params['title'] ?? null;
    $rating = $params['rating'] ?? null;
    $category = $params['category'] ?? null;
    try {
        $movies = $movieDao->retrieveFilms($title, $rating, $category);
        array_walk($movies, function (&$value, $key) {
            $hateoas = new Hateoas();
            $hateoas->addLink("/movie/$value->film_id", 'details');
            $hateoas->addLink("/movie/$value->film_id/actors", 'actors');
            $value = $hateoas->exportWithItem($value);
        });
        return $response->withJson($hateoas->exportWithCollection($movies));
    } catch (\PDOException $e) {
        // TODO: log exception
        
        $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
        return $response->withJson($message, $status = 500);
    }
});

$app->get('/movie/{id}', function (Request $request, Response $response, array $args) {
    $movieDao = $this->get('movieDao');
    
    $hateoas = new Hateoas();
    $hateoas->addLink('/', 'start')->addLink('/movies', 'movies')->addLink("/movie/$args[id]", 'self');
    
    try {
        $movie = $movieDao->retrieveFilmByFilmId($args['id']);
        if (empty($movie)) {
            return $response->withJson([
                'message' => 'Movie not found.'
            ], $status = 404);
        }
        return $response->withJson($hateoas->exportWithItem($movie));
    } catch (\PDOException $e) {
        // TODO: log exception
        
        $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
        return $response->withJson($message, $status = 500);
    }
});

$app->get('/movie/{id}/actors', function (Request $request, Response $response, array $args) {
    $movieDao = $this->get('movieDao');
    
    $hateoas = new Hateoas();
    $hateoas->addLink('/', 'start')->addLink('/movies', 'movies');
    $hateoas->addLink("/movie/$args[id]", 'parent');
    $hateoas->addLink("/movie/$args[id]/actors", 'self');
    
    try {
        $actors = $movieDao->retrieveActorsByFilmId($args['id']);
        array_walk($actors, function (&$value, $key) {
            $hateoas = new Hateoas();
            $value = $hateoas->exportWithItem($value);
        });
        return $response->withJson($hateoas->exportWithCollection($actors));
    } catch (\PDOException $e) {
        // TODO: log exception
        
        $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
        return $response->withJson($message, $status = 500);
    }
});

$app->run();
