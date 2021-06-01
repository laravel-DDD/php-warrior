<?php

namespace PHPWarrior\Abilities;

class DistanceOf extends Base
{
    public bool $isSense = true;

    public function description(): string
    {
        return __("Pass a Space as an argument, and it will return an integer representing the distance to that space.");
    }

    public function perform($space): mixed
    {
        return $this->unit->position->distanceOf($space);
    }
}
