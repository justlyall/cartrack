<?php
namespace Cartrack\Tweets\Sentiments\Classifiers;

use Cartrack\Tweets\Tweet;

interface SentimentClassifiersInterface
{
    public function get(Tweet $tweet): array;
}