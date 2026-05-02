<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

final class PhoneNumber
{
    private function __construct(private readonly string $value) {}

    public static function fromString(string $value): self
    {
        $digits = preg_replace('/\D/', '', $value);

        if ($digits === null || strlen($digits) < 7 || strlen($digits) > 15) {
            throw new \InvalidArgumentException('Invalid phone number format.');
        }

        return new self($digits);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
