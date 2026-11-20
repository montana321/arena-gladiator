<?php
namespace App\Game\Fighters;

class BotGladiator extends Fighter
{
    public function __construct(string $name, string $avatar, int $hp, int $maxHp, int $stamina = 100)
    {
        parent::__construct($name, $avatar, $hp, $maxHp, $stamina);
    }
}