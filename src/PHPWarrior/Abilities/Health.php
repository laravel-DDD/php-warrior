<?php

namespace PHPWarrior\Abilities;

class Health extends Base
{
    public bool $isSense = true;

    public function description(): string
    {
        return __('Returns an integer representing your health.');
    }

    public function perform(): mixed
    {
        return $this->unit->health();
    }
}
