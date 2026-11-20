<?php
namespace App\Game\Fighters;

abstract class Fighter
{
    public function __construct(
        protected string $name,
        protected string $avatar,
        protected int $hp,
        protected int $maxHp,
        protected int $stamina = 100,
        protected bool $isBlocking = false
    ) {}

    public function getName(): string { return $this->name; }
    public function getAvatar(): string { return $this->avatar; }
    public function getHp(): int { return $this->hp; }
    public function getMaxHp(): int { return $this->maxHp; }
    public function getStamina(): int { return $this->stamina; }
    public function isAlive(): bool { return $this->hp > 0; }
    public function isBlocking(): bool { return $this->isBlocking; }

    public function setBlocking(bool $state): void { $this->isBlocking = $state; }

    public function spendStamina(int $amount): bool
    {
        if ($this->stamina < $amount) return false;
        $this->stamina -= $amount;
        return true;
    }

    public function restoreStamina(int $amount): void
    {
        $this->stamina = min(100, $this->stamina + $amount);
    }

    public function heal(int $amount): void
    {
        $this->hp = min($this->maxHp, $this->hp + $amount);
    }

    public function receiveDamage(int $damage): int
    {
        if ($this->isBlocking) {
            $damage = (int) floor($damage * 0.25);
            $this->isBlocking = false;
        }
        $this->hp = max(0, $this->hp - $damage);
        return $damage;
    }
}