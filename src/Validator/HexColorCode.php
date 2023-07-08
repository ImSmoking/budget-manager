<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class HexColorCode extends Constraint
{
    public string $message = 'The value "{{ value }}" is not a valid Hex Color Code';
}