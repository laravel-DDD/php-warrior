<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;

class Feel extends Base
{
    public bool $isSense = true;

    public function description(): string
    {
        return __('Returns a Space for the given direction (forward by default).');
    }

    public function perform(string $direction = 'forward'): mixed
    {
        $direction = Position::normalizeDirection($direction);
        $this->verifyDirection($direction);

        return $this->space($direction);
    }
}
