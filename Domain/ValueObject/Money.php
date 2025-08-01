<?php
// Domain/ValueObject/Money.php
declare(strict_types=1);

namespace Domain\ValueObject;

final readonly class Money
{
    public function __construct(
        public int    $amountMinor, // количество в минорных единицах (копейки)
        public string $currency = 'RUB'
    ) {
        if ($this->amountMinor < 0) throw new \InvalidArgumentException('Money < 0');
    }

    public static function zero(string $currency = 'RUB'): self { return new self(0, $currency); }

    public function add(self $b): self {
        $this->assertSameCurrency($b);
        return new self($this->amountMinor + $b->amountMinor, $this->currency);
    }

    public function mul(int $qty): self {
        return new self($this->amountMinor * $qty, $this->currency);
    }

    public function format(): string {
        return number_format($this->amountMinor / 100, 2, '.', ' ') . ' ' . $this->currency;
    }

    private function assertSameCurrency(self $b): void {
        if ($this->currency !== $b->currency) throw new \DomainException('Currency mismatch');
    }
}