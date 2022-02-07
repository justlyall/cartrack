<?php
namespace Cartrack\Tweets\Exceptions;

class TweetLengthException extends \Exception
{
    public function __construct(string $message = "Tweet length error")
    {
        parent::__construct($message, 0, null);
    }
}