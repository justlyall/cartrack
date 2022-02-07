<?php
namespace Cartrack\Tweets\Exceptions;

class TweetNotFoundException extends \Exception
{
    public function __construct(string $message = "Tweet could not be found")
    {
        parent::__construct($message, 0, null);
    }
}