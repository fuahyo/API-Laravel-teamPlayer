<?php

namespace App\Enums;

class PlayerSkillType
{
    const DEFENSE = 'defense';
    const ATTACK = 'attack';
    const SPEED = 'speed';
    const STRENGTH = 'strength';
    const STAMINA = 'stamina';

    public static function getKeyByValue($value)
    {
        $constants = (new \ReflectionClass(__CLASS__))->getConstants();
        return array_search($value, $constants);
    }
}
