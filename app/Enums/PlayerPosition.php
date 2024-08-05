<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PlayerPosition extends Enum
{
    const DEFENDER = 'defender';
    const MIDFIELDER = 'midfielder';
    const FORWARD = 'forward';
}
