<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;

/**
 * Class Bind 
 * 
 * @package PHPWarrior\Abilities
 */
class Bind extends Base
{
    /**
     * The description of the ability for the unit in the game.
     */
    public function description()
    {
        return __("Binds a unit in given direction to keep him from moving (forward by default).");
    }

    /**
     * Method that performs the binding ability in the game level. 
     * If a unit is binded he cannot do anything more.
     */
    public function perform($direction = 'forward')
    {
        $direction = Position::normalize_direction($direction);
        $this->verify_direction($direction);
        $receiver = $this->unit($direction);

        if ($receiver) {
            $this->unit->say(sprintf(__('binds %1$s and restricts %2$s'), __($direction), $receiver));
            $receiver->bind();
        } else {
            $this->unit->say(sprintf(__("binds %s and restricts nothing"), $direction));
        }
    }
}
