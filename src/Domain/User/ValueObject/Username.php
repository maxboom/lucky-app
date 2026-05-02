<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

final class Username
{
    private function __construct(private readonly string $value) {}

    public static function fromString(string $value): self
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new \InvalidArgumentException('Username cannot be empty.');
        }

        if (strlen($trimmed) > 100) {
            throw new \InvalidArgumentException('Username is too long.');
        }

        return new self($trimmed);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
