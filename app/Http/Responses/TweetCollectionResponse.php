<?php
namespace Cartrack\Http\Responses;

use Laminas\Diactoros\Response\JsonResponse;

class TweetCollectionResponse extends JsonResponse
{
    use TweetResponseTransformerTrait;

    public function __construct(array $tweets, int $status = 200)
    {
        $tweets['tweets'] = $this->transformTweets($tweets['tweets']);
        parent::__construct($tweets, $status);
    }

    private function transformTweets(array $tweets): array
    {
        $transformedTweets = [];
        foreach ($tweets as $tweet) {
            $transformedTweets[] = $this->transform($tweet);
        }
        return $transformedTweets;
    }
}