<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;

class Bind extends Base
{
    public function description(): string
    {
        return __("Binds a unit in given direction to keep him from moving (forward by default).");
    }

    public function perform(string $direction = 'forward'): void
    {
        $direction = Position::normalizeDirection($direction);
        $this->verifyDirection($direction);
        $receiver = $this->unit($direction);

        if ($receiver) {
            $this->unit->say(sprintf(__('binds %1$s and restricts %2$s'), __($direction), $receiver));
            $receiver->bind();
        } else {
            $this->unit->say(sprintf(__("binds %s and restricts nothing"), $direction));
        }
    }
}
