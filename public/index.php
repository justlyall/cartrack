<?php declare(strict_types=1);

use Cartrack\Http\Responses\ErrorResponse;
use Cartrack\Http\Responses\NotFoundResponse;
use Cartrack\Tweets\Exceptions\InvalidRequestBodyException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Http\Exception\NotFoundException;

include '../vendor/autoload.php';
include '../config.php';

/** @var \League\Route\Router $route */
include '../app/routes.php';

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

try {
    $response = $router->dispatch($request);
} catch (InvalidRequestBodyException $exception) {
    $response = new ErrorResponse($exception);
} catch (NotFoundException $exception) {
    $response = new NotFoundResponse($exception);
} catch (\Exception $exception) {
    $response = new JsonResponse('An unknown error has occurred', 500);
}

(new SapiEmitter)->emit($response);
