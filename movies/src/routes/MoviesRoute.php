<?php
namespace routes;

use routes\AbstractRoute;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use rest\Hateoas;

class MoviesRoute extends AbstractRoute
{

    function __invoke(Request $request, Response $response, array $args)
    {
        $movieDao = $this->container->get('movieDao');
        
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
            
            $hateoas = $this->container->get('baseHateoas');
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
    }
}