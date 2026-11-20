<?php
namespace App\Game\Moves;

use App\Game\Fighters\Fighter;

class ShieldBlockMove implements CombatMoveInterface
{
    public function getLabel(): string { return '🛡️ Глухая защита'; }
    public function getStaminaCost(): int { return 0; }

    public function execute(Fighter $attacker, Fighter $defender): array
    {
        $attacker->setBlocking(true);
        $attacker->restoreStamina(25);
        $attacker->heal(8);
        return ['text' => "{$attacker->getName()} поднимает щит, восстанавливает 8 HP и силы!", 'damage' => 0, 'type' => 'block'];
    }
}