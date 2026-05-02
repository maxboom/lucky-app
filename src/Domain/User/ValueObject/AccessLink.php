<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

final class AccessLink
{
    private const TTL_DAYS = 7;

    private function __construct(
        private readonly string $token,
        private readonly \DateTimeImmutable $expiresAt,
        private readonly bool $active,
    ) {}

    public static function generate(): self
    {
        return new self(
            token: bin2hex(random_bytes(32)),
            expiresAt: new \DateTimeImmutable('+' . self::TTL_DAYS . ' days'),
            active: true,
        );
    }

    public static function restore(string $token, \DateTimeImmutable $expiresAt, bool $active): self
    {
        return new self($token, $expiresAt, $active);
    }

    public function deactivate(): self
    {
        return new self($this->token, $this->expiresAt, false);
    }

    public function isValid(): bool
    {
        return $this->active && $this->expiresAt > new \DateTimeImmutable();
    }

    public function token(): string
    {
        return $this->token;
    }

    public function expiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
