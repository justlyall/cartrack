<?php
namespace Cartrack\Http\Responses;

use Cartrack\Tweets\Sentiments\Sentiment;
use Cartrack\Tweets\Tweet;

trait TweetResponseTransformerTrait
{
    public function transform(Tweet $tweet): array
    {
        return [
            'id' => $tweet->getId(),
            'user_id' => $tweet->getUserId(),
            'body' => $tweet->getBody(),
            'created_at' => $tweet->getCreateAt(),
            'updated_at' => $tweet->getUpdatedAt(),
            'sentiments' => array_reduce($tweet->getSentiments(), function (array $carry , Sentiment $sentiment) {
                $carry[] = [
                    'id' => $sentiment->getId(),
                    'sentiment' => $sentiment->getSentiment()
                ];
                return $carry;
            },[])
        ];
    }
}