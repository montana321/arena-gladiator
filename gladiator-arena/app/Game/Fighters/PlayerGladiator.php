<?php
namespace App\Game\Fighters;

class PlayerGladiator extends Fighter
{
    public function __construct(string $name = 'Спартак', int $hp = 100, int $maxHp = 100, int $stamina = 100)
    {
        parent::__construct($name, '🥷', $hp, $maxHp, $stamina);
    }
}