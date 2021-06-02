<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;

class Feel extends Base
{

    public $is_sense = true;

    public function description()
    {
        return __('Returns a Space for the given direction (forward by default).');
    }

    public function perform($direction = 'forward')
    {
        $direction = Position::normalize_direction($direction);
        $this->verify_direction($direction);
        return $this->space($direction);
    }
}
