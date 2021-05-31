<?php

namespace PHPWarrior\Units;

/**
 * Class Sludge
 *
 * @package PHPWarrior\Units
 */
class Sludge extends Base
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->addAbilities(['attack', 'feel']);
    }

    /**
     * Play your turn.
     *
     * @param $turn
     */
    public function playTurn($turn): void
    {
        $directions = ['forward', 'left', 'right', 'backward'];

        foreach ($directions as $direction) {
            if ($turn->feel($direction)->isPlayer()) {
                $turn->attack($direction);
                return;
            }
        }
    }

    /**
     * Your attack power.
     */
    public function attackPower(): int
    {
        return 3;
    }

    /**
     * Maximum health.
     */
    public function maxHealth(): int
    {
        return 12;
    }

    /**
     * Your character.
     */
    public function character(): string
    {
        return "s";
    }
}
