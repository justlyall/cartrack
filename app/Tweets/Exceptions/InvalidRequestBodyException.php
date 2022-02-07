<?php
namespace Cartrack\Tweets\Exceptions;

class InvalidRequestBodyException extends \Exception
{
    public function __construct(string $message = "Could not decode JSON body")
    {
        parent::__construct($message,  0, null);
    }
}