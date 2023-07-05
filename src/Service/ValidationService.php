<?php

namespace App\Service;

use App\Exception\ApiValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    public function __construct(
        private readonly ValidatorInterface $validator
    )
    {
    }


    public function validate(object $object, array $groups): void
    {
        $errors = [];

        $validationErrors = $this->validator->validate($object, null, $groups);

        if ($validationErrors->count() > 0) {
            foreach ($validationErrors as $error) {
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }

            throw ApiValidationException::create('Validation failed', $errors);
        }
    }
}