<?php
namespace Cartrack\Http\Responses;

use Cartrack\Tweets\Tweet;
use Laminas\Diactoros\Response\JsonResponse;

class TweetResponse extends JsonResponse
{
    use TweetResponseTransformerTrait;

    public function __construct(Tweet $tweet, int $status = 200)
    {
        parent::__construct($this->transform($tweet), $status);
    }
}