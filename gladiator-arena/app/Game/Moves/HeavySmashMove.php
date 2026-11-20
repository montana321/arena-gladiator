<?php
namespace App\Game\Moves;

use App\Game\Fighters\Fighter;

class HeavySmashMove implements CombatMoveInterface
{
    public function getLabel(): string { return '⚔️ Сокрушительный удар'; }
    public function getStaminaCost(): int { return 30; }

    public function execute(Fighter $attacker, Fighter $defender): array
    {
        if (!$attacker->spendStamina($this->getStaminaCost())) {
            return ['text' => "{$attacker->getName()} выдохся и промахнулся!", 'damage' => 0, 'type' => 'miss'];
        }
        $attacker->setBlocking(false);
        $dealt = $defender->receiveDamage(28);
        return ['text' => "{$attacker->getName()} наносит тяжелый удар (-{$dealt} HP)!", 'damage' => $dealt, 'type' => 'hit'];
    }
}