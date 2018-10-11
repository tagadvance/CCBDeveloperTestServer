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

$app->get('/', function (Request $request, Response $response, array $args) {
    $hateoas = $this->get('baseHateoas');
    $hateoas->addText('welcome', 'Welcome to Tag\s Developer Test Server.');
    return $response->withJson($hateoas->export());
});

$app->get('/movies', function (Request $request, Response $response, array $args) {
    $movieDao = $this->get('movieDao');
    
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
        
        $hateoas = $this->get('baseHateoas');
        $hateoas->addNamedCollection('ratings', $movieDao->getFilmRatings());
        $hateoas->addNamedCollection('categories', $movieDao->getCategories());
        $hateoas->addText('hint', 'Movies may be filtered by title, rating, or category, e.g. /movies?title=dino&rating=PG&category=Classics');
        $data = $hateoas->exportWithCollection($movies);
        return $response->withJson($data);
    } catch (\PDOException $e) {
        // TODO: log exception
        
        $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
        return $response->withJson($message, $status = 500);
    }
});

$app->get('/movie/{id}', function (Request $request, Response $response, array $args) {
    $movieDao = $this->get('movieDao');
    try {
        $movie = $movieDao->retrieveFilmByFilmId($args['id']);
        if (empty($movie)) {
            return $response->withJson([
                'message' => 'Movie not found.'
            ], $status = 404);
        }
        
        $hateoas = $this->get('baseHateoas');
        $hateoas->addLink("/movie/$args[id]", 'self');
        $data = $hateoas->exportWithItem($movie);
        return $response->withJson($data);
    } catch (\PDOException $e) {
        // TODO: log exception
        
        $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
        return $response->withJson($message, $status = 500);
    }
});

$app->get('/movie/{id}/actors', function (Request $request, Response $response, array $args) {
    $movieDao = $this->get('movieDao');
    
    try {
        $actors = $movieDao->retrieveActorsByFilmId($args['id']);
        array_walk($actors, function (&$value, $key) {
            $hateoas = new Hateoas();
            $value = $hateoas->exportWithItem($value);
        });
        
        $hateoas = $this->get('baseHateoas');
        $hateoas->addLink("/movie/$args[id]", 'parent');
        $hateoas->addLink("/movie/$args[id]/actors", 'self');
        $data = $hateoas->exportWithCollection($actors);
        return $response->withJson($data);
    } catch (\PDOException $e) {
        // TODO: log exception
        
        $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
        return $response->withJson($message, $status = 500);
    }
});

$app->run();
