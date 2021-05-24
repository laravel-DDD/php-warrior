<?php

namespace PHPWarrior\Units;

/**
 * Class ThickSludge
 *
 * @package PHPWarrior\Units
 */
class ThickSludge extends Sludge
{
    /**
     * Maximum health.
     */
    public function maxHealth(): int
    {
        return 24;
    }

    /**
     * Character.
     */
    public function character(): string
    {
        return "S";
    }
}
