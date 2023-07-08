<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class ApiValidationException extends BaseException
{
    public static function create(string $message, array $errors = []): self
    {
        return new self($message, Response::HTTP_UNPROCESSABLE_ENTITY, null, $errors);
    }
}