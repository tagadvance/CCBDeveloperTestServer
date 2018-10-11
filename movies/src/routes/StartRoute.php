<?php
namespace routes;

use routes\AbstractRoute;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class StartRoute extends AbstractRoute
{

    function __invoke(Request $request, Response $response, array $args)
    {
        $hateoas = $this->container->get('baseHateoas');
        $hateoas->addText('welcome', 'Welcome to Tag\s Developer Test Server.');
        return $response->withJson($hateoas->export());
    }
}