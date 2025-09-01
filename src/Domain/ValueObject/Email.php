<?php
namespace App\Domain\ValueObject;

use App\Shared\Exception\DomainException;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException("Email inválido: $value");
        }
        $this->value = strtolower($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
