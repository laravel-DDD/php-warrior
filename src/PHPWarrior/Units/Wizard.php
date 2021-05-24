<?php

namespace PHPWarrior\Units;

/**
 * Class Wizard
 *
 * @package PHPWarrior\Units
 */
class Wizard extends Base
{
    public function __construct()
    {
        $this->addAbilities(['shoot', 'look']);
    }

    public function playTurn($turn)
    {
        $directions = ['forward', 'left', 'right'];

        foreach ($directions as $direction) {
            foreach ($turn->look($direction) as $space) {
                if ($space->isPlayer()) {
                    $turn->shoot($direction);
                    return;
                } elseif (!$space->is_empty()) {
                    break;
                }
            }
        }
    }

    /**
     * Shooting power for the wizard.
     */
    public function shootPower(): int
    {
        return 11;
    }

    /**
     * Maximun health for the wizard
     */
    public function maxHealth(): int
    {
        return 3;
    }

    /**
     * The character for the unit. This character will be used in the mapping of the level.
     */
    public function character(): string
    {
        return "w";
    }
}
