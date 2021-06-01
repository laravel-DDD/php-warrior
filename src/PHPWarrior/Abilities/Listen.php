<?php

namespace PHPWarrior\Abilities;

class Listen extends Base
{
    public bool $isSense = true;

    public function description(): string
    {
        return __("Returns an array of all spaces which have units in them.");
    }

    public function perform(): array
    {
        $map = [];

        foreach ($this->unit->position->floor->units() as $unit) {
            if ($unit !== $this->unit) {
                $map[] = $unit->position->space();
            }
        }

        return $map;
    }
}
