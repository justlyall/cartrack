<?php
namespace Cartrack\Tweets\Exceptions;

class MissingPropertyException extends \Exception
{
    public function __construct(string $property)
    {
        parent::__construct($property .  " is a required property for this endpoint", 0, null);
    }
}