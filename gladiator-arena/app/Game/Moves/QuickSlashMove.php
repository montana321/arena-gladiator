<?php
namespace App\Game\Moves;

use App\Game\Fighters\Fighter;

class QuickSlashMove implements CombatMoveInterface
{
    public function getLabel(): string { return '🗡️ Быстрый выпад'; }
    public function getStaminaCost(): int { return 0; }

    public function execute(Fighter $attacker, Fighter $defender): array
    {
        $attacker->setBlocking(false);
        $attacker->restoreStamina(15);
        $isCrit = rand(1, 100) <= 30;
        $raw = $isCrit ? 24 : 12;
        $dealt = $defender->receiveDamage($raw);
        $critText = $isCrit ? ' КРИТИЧЕСКИЙ УДАР!' : '';
        return ['text' => "{$attacker->getName()} делает быстрый выпад (-{$dealt} HP).{$critText}", 'damage' => $dealt, 'type' => 'hit'];
    }
}