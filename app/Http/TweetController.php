<?php
namespace Cartrack\Http;

use Cartrack\Http\Responses\ErrorResponse;
use Cartrack\Http\Responses\NotFoundResponse;
use Cartrack\Http\Responses\TweetCollectionResponse;
use Cartrack\Http\Responses\TweetResponse;
use Cartrack\Tweets\Exceptions\InvalidRequestBodyException;
use Cartrack\Tweets\Exceptions\MissingPropertyException;
use Cartrack\Tweets\Exceptions\TweetLengthException;
use Cartrack\Tweets\Exceptions\TweetNotFoundException;
use Cartrack\Tweets\Tweet;
use Cartrack\Tweets\TweetCreator;
use Cartrack\Tweets\TweetDeleter;
use Cartrack\Tweets\TweetFinder;
use Cartrack\Tweets\TweetRepository;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ResponseInterface;

class TweetController
{
    public function search(ServerRequest $serverRequest, TweetFinder $tweetFinder): ResponseInterface
    {
        return new TweetCollectionResponse($tweetFinder->find($serverRequest));
    }

    public function create(ServerRequest $request, TweetCreator $tweetCreator): ResponseInterface
    {
        $request = $this->parseRequestBody($request);

        try {
            $this->validateTweetRequest($request);
            $tweet = $tweetCreator->createOrUpdate(new Tweet(null, 1, $request->body));
            return new TweetResponse($tweet);
        } catch (TweetLengthException | MissingPropertyException $exception) {
            return new ErrorResponse($exception);
        }
    }

    public function update(ServerRequest $request, TweetCreator $tweetCreator, TweetRepository $repository): ResponseInterface
    {
        $jsonRequest = $this->parseRequestBody($request);

        try {
            $this->validateTweetRequest($jsonRequest);
            $tweet = $repository->firstOrFail((int) $request->getAttribute('id', 0));
            $tweet->setBody($jsonRequest->body);
            $tweet = $tweetCreator->createOrUpdate($tweet);

            return new TweetResponse($tweet, 204);
        } catch (TweetNotFoundException $exception) {
            return new NotFoundResponse($exception);
        } catch (TweetLengthException | MissingPropertyException $exception) {
            return new ErrorResponse($exception);
        }
    }

    public function delete(ServerRequest $request, TweetDeleter $deleter, TweetRepository $repository): ResponseInterface
    {
        try {
            $tweet = $repository->firstOrFail((int) $request->getAttribute('id', 0));
            $deleter->delete($tweet);
            return new JsonResponse(['message' => 'Tweet deleted fully'], 204);
        } catch (TweetNotFoundException $exception) {
            return new NotFoundResponse($exception);
        }
    }

    public function view(ServerRequest $request, TweetRepository $tweetRepository): ResponseInterface
    {
        try {
            $tweet = $tweetRepository->findWithSentiments((int) $request->getAttribute('id', 0));
            return new TweetResponse($tweet);
        } catch (TweetNotFoundException $exception) {
            return new NotFoundResponse($exception);
        }
    }

    private function parseRequestBody(ServerRequest $serverRequest): \stdClass
    {
        $request = json_decode($serverRequest->getBody()->getContents());
        if (json_last_error() === JSON_ERROR_NONE) {
            return $request;
        }
        throw new InvalidRequestBodyException();
    }

    private function validateTweetRequest(\stdClass $request)
    {
        if (empty($request->body)) {
            throw new MissingPropertyException('body');
        }
    }
}