<?php
namespace Cartrack\Http\Responses;

class NotFoundResponse extends ErrorResponse
{
    public function __construct(\Exception $exception, int $status = 404)
    {
        parent::__construct($exception, $status);
    }
}
