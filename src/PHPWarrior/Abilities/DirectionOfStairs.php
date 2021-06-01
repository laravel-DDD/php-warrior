<?php

namespace PHPWarrior\Abilities;

class DirectionOfStairs extends Base
{
    public bool $isSense = true;

    public function description(): string
    {
        return __("Returns the direction (left, right, forward, backward) the stairs are from your location.");
    }

    public function perform(): mixed
    {
        return $this->unit->position->relativeDirectionOfStairs();
    }
}
