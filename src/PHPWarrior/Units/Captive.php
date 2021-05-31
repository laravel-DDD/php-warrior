<?php

namespace PHPWarrior\Units;

/**
 * Class Captive
 *
 * @package PHPWarrior\Units
 */
class Captive extends Base
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->bind();
    }

    /**
     * Maximum health.
     *
     * @return int
     */
    public function maxHealth(): int
    {
        return 1;
    }

    /**
     * Character type.
     */
    public function character(): string
    {
        return "C";
    }
}
