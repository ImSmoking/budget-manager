<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class BaseException extends Exception
{
    public function __construct(
        string       $message = "",
        int          $code = 0,
        ?Throwable   $previous = null,
        public array $extraData = []
    )
    {
        parent::__construct($message, $code, $previous);
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }
}