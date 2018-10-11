<?php
namespace routes;

use routes\AbstractRoute;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use rest\Hateoas;

class MovieRoute extends AbstractRoute
{

    function __invoke(Request $request, Response $response, array $args)
    {
        $movieDao = $this->container->get('movieDao');
        try {
            $movie = $movieDao->retrieveFilmByFilmId($args['id']);
            if (empty($movie)) {
                return $response->withJson([
                    'message' => 'Movie not found.'
                ], $status = 404);
            }
            
            $hateoas = $this->container->get('baseHateoas');
            $hateoas->addLink("/movie/$args[id]", 'self');
            $data = $hateoas->exportWithItem($movie);
            return $response->withJson($data);
        } catch (\PDOException $e) {
            // TODO: log exception
            
            $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
            return $response->withJson($message, $status = 500);
        }
    }
}