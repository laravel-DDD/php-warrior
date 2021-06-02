<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;

class Walk extends Base
{

    public function description()
    {
        return __("Move in the given direction (forward by default).");
    }

    public function perform($direction = 'forward')
    {
        $direction = Position::normalize_direction($direction);
        $this->verify_direction($direction);

        if ($this->unit->position) {
            $s_direction = __(str_replace(':', '', $direction));
            $this->unit->say(sprintf(__("walks %s"), $s_direction));

            if ($this->space($direction)->is_empty()) {
                call_user_func_array([$this->unit->position, 'move'], $this->offset($direction));
            } else {
                $this->unit->say(sprintf(__("bumps into %s"), $this->space($direction)));
            }
        }
    }
}
