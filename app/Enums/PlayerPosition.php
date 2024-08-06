<?php

namespace App\Enums;

class PlayerPosition
{
    const DEFENDER = 'defender';
    const MIDFIELDER = 'midfielder';
    const FORWARD = 'forward';

    public static function getKeyByValue($value)
    {
        $constants = (new \ReflectionClass(__CLASS__))->getConstants();
        return array_search($value, $constants);
    }
}
