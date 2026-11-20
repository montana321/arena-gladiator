<?php
namespace App\Game\Moves;

use App\Game\Fighters\Fighter;

interface CombatMoveInterface
{
    public function getLabel(): string;
    public function getStaminaCost(): int;
    public function execute(Fighter $attacker, Fighter $defender): array;
}