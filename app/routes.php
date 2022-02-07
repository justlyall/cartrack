<?php

use Cartrack\Db\Database;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use League\Route\Router;

$router = new Router();

$router->map('POST', '/tweet', function (ServerRequestInterface $request): ResponseInterface {
    connectToDatabase();
    return (new \Cartrack\Http\TweetController())->create(
        $request,
        new \Cartrack\Tweets\TweetCreator(
            new \Cartrack\Tweets\TweetRepository(),
            new \Cartrack\Tweets\Sentiments\Classifiers\GoogleSentimentClassifier(
                new \Google\Cloud\Language\V1\LanguageServiceClient(
                    [
                        'credentials' => json_decode(file_get_contents('../noble-airport-397-0c1e0f70272d.json'), true)
                    ]
                )
            ),
            new \Cartrack\Tweets\Sentiments\SentimentRepository()
        )
    );
});

$router->map('PUT', '/tweet/{id}', function (ServerRequestInterface $request, array $args): ResponseInterface {
    connectToDatabase();
    return (new \Cartrack\Http\TweetController())->update(
        $request,
        new \Cartrack\Tweets\TweetCreator(
            new \Cartrack\Tweets\TweetRepository(),
            new \Cartrack\Tweets\Sentiments\Classifiers\GoogleSentimentClassifier(
                new \Google\Cloud\Language\V1\LanguageServiceClient(
                    [
                        'credentials' => json_decode(file_get_contents('../noble-airport-397-0c1e0f70272d.json'), true)
                    ]
                )
            ),
            new \Cartrack\Tweets\Sentiments\SentimentRepository()
        ),
        new \Cartrack\Tweets\TweetRepository()
    );
});

$router->map('DELETE', '/tweet/{id}', function (ServerRequestInterface $request, array $args): ResponseInterface {
    connectToDatabase();
    return (new \Cartrack\Http\TweetController())->delete(
        $request,
        new \Cartrack\Tweets\TweetDeleter(
            new \Cartrack\Tweets\TweetRepository()
        ),
        new \Cartrack\Tweets\TweetRepository()
    );
});

$router->map('GET', '/tweet/{id}', function (ServerRequestInterface $request, array $args): ResponseInterface {
    connectToDatabase();
    return (new \Cartrack\Http\TweetController())->view($request, new \Cartrack\Tweets\TweetRepository());
});

$router->map('GET', '/tweets', function (ServerRequestInterface $request, array $args): ResponseInterface {
    connectToDatabase();

    return (new \Cartrack\Http\TweetController())->search(
        $request,
        new Cartrack\Tweets\TweetFinder(new \Cartrack\Tweets\TweetRepository($pdo))
    );
});

function connectToDatabase()
{
    global $config;
    Database::connect($config['database']['testing']);
}
