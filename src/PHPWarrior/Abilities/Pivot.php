<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;

class Pivot extends Base
{
    public static $ROTATION_DIRECTIONS = ['forward', 'right', 'backward', 'left'];

    public function description()
    {
        return __("Rotate left, right or backward (default)");
    }

    public function perform($direction = 'backward')
    {
        $direction = Position::normalize_direction($direction);

        $this->verify_direction($direction);
        
        $this->unit->position->rotate(array_search($direction, self::$ROTATION_DIRECTIONS));
        $this->unit->say(sprintf(__("pivots %s"), __($direction)));
    }
}
