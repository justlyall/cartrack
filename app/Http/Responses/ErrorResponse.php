<?php
namespace Cartrack\Http\Responses;

use Laminas\Diactoros\Response\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(\Exception $exception, int $status = 400)
    {
        parent::__construct($exception->getMessage(), $status);
    }
}
