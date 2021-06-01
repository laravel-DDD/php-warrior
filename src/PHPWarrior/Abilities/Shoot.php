<?php

namespace PHPWarrior\Abilities;

class Shoot extends Base
{
    public function description(): string
    {
        return __("Shoot your bow & arrow in given direction (forward by default).");
    }

    public function perform(string $direction = 'forward'): void
    {
        $direction = \PHPWarrior\Position::normalize_direction($direction);
        $this->verifyDirection($direction);
        $receiver = null;

        foreach ($this->multiUnit($direction, range(1, 3)) as $row) {
            if (!is_null($row)) {
                $receiver = $row;
                break;
            }
        }

        if ($receiver) {
            $this->unit->say(sprintf(__('shoots %1$s and hits %2$s'), __($direction), $receiver));
            $this->damage($receiver, $this->unit->shootPower());
        } else {
            $this->unit->say(__("shoots and hits nothing"));
        }
    }

    public function multiUnit($direction, $range): array
    {
        $map = [];

        foreach ($range as $n) {
            $map[] = $this->unit($direction, $n);
        }

        return $map;
    }
}
