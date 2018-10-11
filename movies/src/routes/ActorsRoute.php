<?php
namespace routes;

use routes\AbstractRoute;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use rest\Hateoas;

class ActorsRoute extends AbstractRoute
{

    function __invoke(Request $request, Response $response, array $args)
    {
        $movieDao = $this->container->get('movieDao');
        
        try {
            $actors = $movieDao->retrieveActorsByFilmId($args['id']);
            array_walk($actors, function (&$value, $key) {
                $hateoas = new Hateoas();
                $value = $hateoas->exportWithItem($value);
            });
            
            $hateoas = $this->container->get('baseHateoas');
            $hateoas->addLink("/movie/$args[id]", 'parent');
            $hateoas->addLink("/movie/$args[id]/actors", 'self');
            $data = $hateoas->exportWithCollection($actors);
            return $response->withJson($data);
        } catch (\PDOException $e) {
            // TODO: log exception
            
            $message = Hateoas::exportMessage('An error has occurred. Please check the log for more information.');
            return $response->withJson($message, $status = 500);
        }
    }
}