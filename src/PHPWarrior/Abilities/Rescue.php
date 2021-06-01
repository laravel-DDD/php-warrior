<?php

namespace PHPWarrior\Abilities;

use PHPWarrior\Position;
use PHPWarrior\Units\Captive;

class Rescue extends Base
{
    public function description(): string
    {
        return __("Rescue a captive from his chains (earning 20 points) in given direction (forward by default).");
    }

    public function perform($direction = 'forward'): void
    {
        $direction = Position::normalizeDirection($direction);
        $this->verifyDirection($direction);

        if ($this->space($direction)->isCaptive()) {
            $recipient = $this->unit($direction);
            $this->unit->say(sprintf(__('unbinds %1$s and rescues %2$s'), __($direction), $recipient));
            $recipient->unbind();

            if (is_a($recipient, Captive::class)) {
                $recipient->position = null;
                $this->unit->earnPoints(20);
            }
        } else {
            $this->unit->say(sprintf(__("unbinds %s and rescues nothing"), __($direction)));
        }
    }
}
