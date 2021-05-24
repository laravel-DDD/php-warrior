<?php

namespace PHPWarrior\Units;

/**
 * Class Archer
 *
 * @package PHPWarrior\Units
 */
class Archer extends Base
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->addAbilities(['shoot', 'look']);
    }

    /**
     * Play turn.
     *
     * @param $turn
     */
    public function playTurn($turn)
    {
        $directions = ['forward', 'left', 'right'];

        foreach ($directions as $direction) {
            foreach ($turn->look($direction) as $space) {
                if ($space->isPlayer()) {
                    $turn->shoot($direction);
                    return;
                } elseif (! $space->isEmpty()) {
                    break;
                }
            }
        }
    }

    /**
     * Shooting power for the ranger.
     */
    public function shootPower(): int
    {
        return 3;
    }

    /**
     * Maximun health
     */
    public function maxHealth(): int
    {
        return 7;
    }

    /**
     * Type of character.
     */
    public function character(): string
    {
        return "a";
    }
}
