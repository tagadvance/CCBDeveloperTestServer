<?php
namespace routes;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class AbstractRoute
{

    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    abstract function __invoke(Request $request, Response $response, array $args);
}