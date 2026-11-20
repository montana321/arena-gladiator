<?php
namespace App\Game\Ai;

use App\Game\Fighters\Fighter;
use App\Game\Moves\{CombatMoveInterface, HeavySmashMove, QuickSlashMove, ShieldBlockMove};

class SmartBotStrategy
{
    public function chooseMove(Fighter $bot, Fighter $player): CombatMoveInterface
    {
        if ($bot->getHp() < 30 && rand(1, 100) <= 50) {
            return new ShieldBlockMove();
        }
        if ($bot->getStamina() >= 30 && rand(1, 100) <= 60) {
            return new HeavySmashMove();
        }
        return new QuickSlashMove();
    }
}