<?php

namespace Framework\Validation;

class ValidationException extends \Exception
{
    protected array $errors = [];
    public function setErrors(array $errors): static
    {
        $this->errors = $errors;
        return $this;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}