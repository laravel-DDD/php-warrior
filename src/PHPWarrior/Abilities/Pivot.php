<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;

class Pivot extends Base
{
    public static array $rotationDirections = ['forward', 'right', 'backward', 'left'];

    public function description(): string
    {
        return __("Rotate left, right or backward (default)");
    }

    public function perform(string $direction = 'backward'): void
    {
        $direction = Position::normalizeDirection($direction);

        $this->verifyDirection($direction);

        $this->unit->position->rotate(array_search($direction, self::$rotationDirections));
        $this->unit->say(sprintf(__("pivots %s"), __($direction)));
    }
}
