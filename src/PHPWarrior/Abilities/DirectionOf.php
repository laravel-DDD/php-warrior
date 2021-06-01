<?php

namespace PHPWarrior\Abilities;

class DirectionOf extends Base
{
    public bool $isSense = true;

    public function description(): string
    {
        return __("Pass a Space as an argument, and the direction (left, right, forward, backward) to that space will be returned.");
    }

    public function perform($space): mixed
    {
        return $this->unit->position->relativeDirectionOf($space);
    }
}
